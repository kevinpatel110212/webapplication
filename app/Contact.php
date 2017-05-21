<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Yajra\Datatables\Datatables;

class Contact extends Model 
{

    
    protected $table = 'contacts';
    protected $fillable = ['id','name','phone_number','created_at','updated_at'];

    public static function dataOperation($request)
    {
        if($request->method() == 'GET')
        {
        	 if ($request->datatable == 'yes') {

                return Datatables::of(self::select('*'))
                                ->addColumn('action', function ($data) {
                                    $tableName = with(new static)->getTable();
                                    $string = "<a href='javascript:;' onclick=edit('$data->id','$tableName') class='btn btn-xs btn-primary'><i class='glyphicon glyphicon-edit'></i> Edit</a>"
                                            . "<a href='javascript:;' onclick=destroyFinally('$data->id','$tableName') class='btn btn-xs btn-danger'><i class='glyphicon glyphicon-remove-circle'></i> Delete</a>";
                                    return $string;
                                })
                                ->make(true);
            }
        	if($request->id && $request->delete == 'yes')
        	{
                 return Contact::delete($request->id);
        	}
            if($request->id)
            {
                return Contact::find($request->id);
            }
            else
            {
                return Contact::all();
            }
        }
        if($request->method() == 'POST')
        {
            Contact::validator($request->all())->validate();
            if($request->id)
            {
                $Contact = Contact::find($request->id);
                $Contact->update($request->all());
            }
            else
            {
                $Contact = new Contact();
                return $Contact->create($request->all());
            }
        }
    }

    protected static function validator($request) 
    {
                return Validator::make(
                                $request, [
                            'name' => 'required|max:50',
                            'phone_number' => 'required',
                            
                ]);
    }
}
