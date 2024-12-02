<?php

namespace App\Http\Controllers;

use App\Models\Job_title;
use App\Models\Room;
use App\Models\Training;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class testcontroller extends Controller
{
    public function index()
    {

        exit;
        $d = Job_title::getRole('Занимающийся');
        dd($d);
        $t = new RoomsController();
        dump($t->showTable());
    }

    public static function exel($file)
    {
        $user = new FastExcel();
        $user->import($file, function ($line) {
            $name = explode(" ", trim($line['фио']));
            //dump($name);
            if (isset($name[1])){
                Users::create([
                    'name' => $name[1],
                    'surname' => $name[0] ?? null,
                    'second_name' => $name[2] ?? null,
                    'job_title' => 'Занимающийся',
                    'phone' => null,
                    'login' => null,
                    'password' => null,
                    'role' => Job_title::getRole('Занимающийся')
                ]);
            }
        });
        return true;
    }
}
