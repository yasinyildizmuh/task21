<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nette\Utils\Arrays;

class Task extends Model
{
    use HasFactory;

    use HasFactory;

    const DOING = 0;
    const TODO = 1;
    const DONE = 2;


    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status'
    ];

    public static function sortByStatus()
    {
        $doingArr = [];
        $todoArr = [];
        $doneArr = [];
        $tasks = Task::all();
        foreach ($tasks as $task) {
            if ($task->status === Task::DOING) {
                $doingArr[] = $task;
            } elseif ($task->status === Task::TODO) {
                $todoArr[] = $task;
            } else {
                $doneArr[] = $task;
            }
        }

        return collect(array_merge(self::customMySort($doingArr), self::customMySort($todoArr), self::customMySort($doneArr)));

    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function customMySort($array){
        $left = [];
        foreach ($array as $key=>$d) {
            foreach ($array as $key2=>$e){
                $splitArrFirst = explode(" ", $array[$key]['title']);
                $uniqueCharFirst = $splitArrFirst[1];
                $splitArrSecond = explode(" ", $array[$key2]['title']);
                $uniqueCharSecond = $splitArrSecond[1];
                if(ord($uniqueCharFirst) < ord($uniqueCharSecond)){
                    $temp = $array[$key];
                    $array[$key] = $array[$key2];
                    $array[$key2] = $temp;
                }
            }
        }

        return $array;
    }

}
