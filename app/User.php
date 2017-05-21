<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Yajra\Datatables\Datatables;
use Validator;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function dataOperation($request) {
        if ($request->method() == 'GET') {

            if ($request->datatable == 'yes') {

                return Datatables::of(self::select('*'))
                                ->addColumn('action', function ($data) {
                                    $tableName = with(new static)->getTable();
                                    $string = "<a href='javascript:;' onclick=edit('$data->id','$tableName') class='btn btn-xs btn-primary'><i class='glyphicon glyphicon-edit'></i> Edit</a>"
                                            . "<a href='javascript:;' onclick=destroyFinally('$data->id','$tableName') class='btn btn-xs btn-danger'><i class='glyphicon glyphicon-remove-circle'></i> Delete</a>";
                                    return $string;
                                })
                                ->editColumn('status', function ($data) {
                                    if ($data->status == 1) {
                                        return 'Active';
                                    } else {
                                        return 'Inactive';
                                    }
                                })
                                ->make(true);
            }

            if ($request->delete == 'yes' && $request->id) {
                self::find($request->id)->delete();
                return 'done';
            }

            if ($request->id) {
                return self::find($request->id);
            } 
                
            return self::all();

        }
        
        if ($request->method() == 'POST') {
            self::validator($request->all())->validate();
            if ($request->id) {
                self::find($request->id)->update($request->all());
                return 'done';
            } else {
                return self::create($request->all());
            }
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $request
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected static function validator($request) {
        return Validator::make(
                        $request, [
                    'email' => 'required|unique:' . with(new static)->getTable() . ',email,' . $request['id'] . '',
                    'name' => 'required',
        ]);
    }
}
