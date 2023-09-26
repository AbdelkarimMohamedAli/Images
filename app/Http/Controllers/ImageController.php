<?php

namespace App\Http\Controllers;

use App\Graphs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Facades\Image as ImageF;
use App\Personainfo;
use App\Subscribe;
use Illuminate\Support\Facades\Storage;
use App\Image;
use Carbon\Carbon;
class ImageController extends Controller
{
    private $governorates=array(
        1=>'القاهرة',
        2=>'الإسكندرية',
        3=>'بورسعيد',
        4=>'السويس',
        11=>'دمياط',
        12=>'الدقهلية',
        13=>'الشرقية',
        14=>'القليوبية',
        15=>'كفر الشيخ',
        16=>'الغربية',
        17=>'المنوفية',
        18=>'البحيرة',
        19=>'الإسماعيلية',
        21=>'الجيزة',
        22=>'بني سويف',
        23=>'الفيوم',
        24=>'المنيا',
        25=>'أسيوط',
        26=>'سوهاج',
        27=>'قنا',
        28=>'أسوان',
        29=>'الأقصر',
        31=>'البحر الأحمر',
        32=>'الوادي الجديد',
        33=>'مطروح',
        34=>'شمال سيناء',    
        35=>'جنوب سيناء',
        88=>'خارج الجمهوريه');
    public function upload(Request $request)
    {
        $imageNames = [];
        foreach ($request->file('files') as $key=> $file) {
            $time = time();
            $tempName = $key.'-'.$time . '.' . $file->getClientOriginalExtension();
            $ss = $file->storeAs('public/images', $tempName);
            $imageNames[] = $ss;
            Image::create(['name' => $ss,'status'=>'pending']);
        }
    
        return back()
            ->with('success', 'Images uploaded successfully.')
            ->with('imageNames', $imageNames);
    }
    public function cron($id)
    {
        $skip = (int)$id*5;
        $data = Image::where('status','pending')->take(1)->skip($skip)->orderby('_id','desc')->first();

        $height = ImageF::make(Storage::get($data['name']))->height();
        $width = ImageF::make(Storage::get($data['name']))->width();
        $height_ = intval($height/5);
        $width_ = intval($width/2);
        $img = ImageF::make(Storage::get($data['name']));
        $img->crop($width_, $height_, 25, 25);
        $img->save('small/'.$data['_id'].'.jpg', 100);


        $url = 'https://elc.thinkapp.org/public/code/?image='.$data['_id'].'.jpg';
        $contents = file_get_contents($url);
        preg_match_all('!\d+!', $contents, $contents);
    if(isset($contents[0][0])){



        if (strlen($contents[0][0]) == 14) {

            $duplication = Image::where('national_id',(int)$contents[0][0])->first();
            if(isset($duplication)){
                $data['national_id'] = (int)$contents[0][0];
                $data['status'] = 'duplication';
                $data->save();
            }else{
                $national_id = $this->national_id_format($contents[0][0]);

                $data['national_id'] = (int)$contents[0][0];
                $data['status'] = 'valid';
                $data['years'] = (int)$national_id['years'];
                $data['month'] = (int)$national_id['month'];
                $data['day'] = (int)$national_id['day'];
                $data['governorates'] = (int)$national_id['governorates'];
                $data['gender'] = (int)$national_id['gender'];
                $data->save();
                $this->national_id_data($data);
            }
        }else{
            $data['national_id'] = 0;
            $data['status'] = 'rejected';
            $data->save();
        }

    }else{
        $data['national_id'] = 0;
        $data['status'] = 'rejected';
        $data->save();
    }
    }



    public function national_id_format($id)
    {
        $id = str_replace('/','',$id);
        $id = str_replace('-','',$id);
        $id = str_replace(' ','',$id);
        $id = str_replace('.','',$id);
        $id = str_replace('"','',$id);
        $id = str_replace("'","",$id);
        $id = str_split($id);
        if($id[0] == 2){
            $years = '19'.$id[1].$id[2];
        }else{
            $years =  '20'.$id[1].$id[2];
        }
        $gender = $id[9].$id[10].$id[11].$id[12] / 2;
        if(strpos($gender,".") !== false){
            $gender = 1;
        }else{
            $gender = 2;
        }
        $national_id['years'] = $years;
        $national_id['month'] = $id[3].$id[4];
        $national_id['day'] = $id[5].$id[6];
        $national_id['governorates'] = $id[7].$id[8];
        $national_id['gender'] = $gender;
        return $national_id;
    }

    // note: should get gov name
    // governorates, gender, day, month,year
    public function national_id_data(Request $data)
    {
        $data=$data->all();
        // make gov graph
        $this->all_count();
        $this->age_graph($data);
        $this->gov_graph($data);
        $this->gender_graph($data);
        $this->gov_gender($data);
        $this->gov_age($data);
        $all=Graphs::all();
        return $this->parse_data($all);
        return $all;

       
    }

    private function parse_data($all)
    {
        $data=[];
        foreach ($all as $obj)
        {
            if($obj['graph']=='count')
            {
                $data['count']=$obj['data'];
            }
            elseif($obj['graph']=='gov')
            {
                $data['gov']=$obj['data'];
            }
            elseif($obj['graph']=='gender')
            {
                $data['gender']=$obj['data'];
            }
            elseif($obj['graph']=='gov_gender')
            {
                unset($obj['_id']);
                unset($obj['graph']);
                unset($obj['updated_at']);
                unset($obj['created_at']);
                $data['gov_gender']=$obj;
            }
            elseif($obj['graph']=='gov_age')
            {
                unset($obj['_id']);
                unset($obj['graph']);
                unset($obj['updated_at']);
                unset($obj['created_at']);
                $data['gov_age']=$obj;
            }
        }
        return $data;
    }
    private function get_age_obj($data,$gov_obj)
    {
        $birthday = Carbon::createFromDate((int)$data['year'],(int)$data['month'], (int)$data['day']);
        $age = $birthday->age;
        if( $age <= 35)
            {
                if(isset($gov_obj['less_35']))
                {
                    $gov_obj['less_35']=(int)$gov_obj['less_35']+1;


                }else{
                    $gov_obj['less_35']=1;
                }
            }
            elseif( 36 <= $age && $age <= 50 )
            {
                if(isset($gov_obj['bet_36_50']))
                {
                    $gov_obj['bet_36_50']=(int)$gov_obj['bet_36_50']+1;
                }else{
                     $gov_obj['bet_36_50']=1;
                }
            }
            elseif( 51 <= $age && $age <= 60 )
            {
                if(isset($gov_obj['bet_51_60']))
                {
                    $gov_obj['bet_51_60']=(int)$gov_obj['bet_51_60']+1;
                }else{
                    $gov_obj['bet_51_60']=1;
                }
            }
            elseif($age > 60 )
            {
                if(isset($gov_obj['more_60']))
                {
                    $gov_obj['more_60']=(int)$gov_obj['more_60']+1;
                }else{
                    $gov_obj['more_60']=1;
                }
            }

            return $gov_obj;
    }

    private function gov_age($data)
    {
        $graph_gov=Graphs::where('graph','gov_age');
        $data['governorates']=$this->governorates[(int)$data['governorates']];
        $birthday = Carbon::createFromDate((int)$data['year'],(int)$data['month'], (int)$data['day']);
        $age = $birthday->age;
        if($graph_gov->exists())
        {
            $graph_gov=$graph_gov->first();
            $gov_obj=$graph_gov[$data['governorates']];
            $gov_obj=$this->get_age_obj($data,$gov_obj);
            $graph_gov[$data['governorates']]=$gov_obj;
            $graph_gov->save();
        }else
        {
            if($age <= 35)
            {
                Graphs::create(['graph'=>'gov_age',$data['governorates']=>['less_35'=> 1]]);
            }
            elseif (36 <= $age && $age <= 50)
            {
                Graphs::create(['graph'=>'gov_age',$data['governorates']=>['bet_36_50'=> 1]]);
            }
            elseif (51 <= $age && $age <= 60)
            {
                Graphs::create(['graph'=>'gov_age',$data['governorates']=>['bet_51_60'=> 1]]);
            }
            elseif ( $age >= 60)
            {
                Graphs::create(['graph'=>'gov_age',$data['governorates']=>['more_60'=> 1]]);
            }
        }
    }
     private function gov_gender($data)
    {
        $graph_gov=Graphs::where('graph','gov_gender');
        $data['governorates']=$this->governorates[(int)$data['governorates']];
        $data['gender']=$data['gender']==1?'ذكر':'انثى';

        if($graph_gov->exists())
        {
            $graph_gov=$graph_gov->first();
            $gov_obj=$graph_gov[$data['governorates']];

            if(isset($gov_obj[$data['gender']]))
            {
                $gov_obj[$data['gender']]=$gov_obj[$data['gender']]+1;
            }else
            {
                $gov_obj[$data['gender']]=1;
            }
            $graph_gov[$data['governorates']]=$gov_obj;
            $graph_gov->save();
        }else
        {
            Graphs::create([
                'graph'=>'gov_gender',
                $data['governorates']=>
                [
                    $data['gender']=>1
                ]
            ]);
        }
    }
    private function age_graph($data)
    {
        $day = (int)$data['day'];
        $month = (int)$data['month'];
        $year = (int)$data['year'];
        $birthday = Carbon::createFromDate($year, $month, $day);
        $age = $birthday->age;
        $graph_age=Graphs::where('graph','age');

        if($graph_age->exists())
        {
            $graph_age= $graph_age->first();

            $graph_age=$this->get_age_obj($data,$graph_age);
         
            $graph_age->save();

        }else{

            if($age <= 35)
            {
                Graphs::create(['graph'=>'age','less_35'=> 1]);
            }
            elseif (36 <= $age && $age <= 50)
            {
                Graphs::create(['graph'=>'age','bet_36_50'=> 1]);
            }
            elseif (51 <= $age && $age <= 60)
            {
                Graphs::create(['graph'=>'age','bet_51_60'=> 1]);
            }
            elseif ( $age >= 60)
            {
                Graphs::create(['graph'=>'age','more_60'=> 1]);
            }

        }


       
       
        

    }
    private function all_count()
    {
        $graph_count=Graphs::where('graph','count');
        if($graph_count->exists())
        {
            $graph_count=$graph_count->first();
            $graph_count['data']= $graph_count['data']+1;
            $graph_count->save();

        }
        else
        {
            Graphs::create(['graph'=>'count','data'=>1]);
        }
    }
    private function gender_graph($data)
    {
        $graph_gender=Graphs::where('graph','gender');
        $data['gender']=$data['gender']==1?'ذكر':'انثى';
        if($graph_gender->exists())
        {
            $graph_gender=$graph_gender->first();
            // update count of governorate if exist
            $graph_gender_data=$graph_gender['data'];
            if(isset($graph_gender_data[$data['gender']]))
            {
             $graph_gender_data[$data['gender']]=(int)$graph_gender_data[$data['gender']]+1;

            }else
            {
                $graph_gender_data[$data['gender']]=1;
            }
            $graph_gender['data']=$graph_gender_data;
            $graph_gender->save();

            
        }else
        {
            Graphs::create(['graph'=>'gender','data'=>[$data['gender']=> 1]]);
        }

    }

    private function gov_graph($data)
    {
        $graph_gov=Graphs::where('graph','gov');
        $data['governorates']=$this->governorates[(int)$data['governorates']];
        if($graph_gov->exists())
        {
            $graph_gov=$graph_gov->first();
            // update count of governorate if exist
            $graph_gov_data=$graph_gov['data'];
            if(isset($graph_gov_data[$data['governorates']]))
            {
             $graph_gov_data[$data['governorates']]=(int)$graph_gov_data[$data['governorates']]+1;

            }else
            {
                $graph_gov_data[$data['governorates']]=1;
            }
            $graph_gov['data']=$graph_gov_data;
            $graph_gov->save();
        }else
        {

            Graphs::create(['graph'=>'gov','data'=>[$data['governorates']=> 1]]);
        }
    }

   


}
