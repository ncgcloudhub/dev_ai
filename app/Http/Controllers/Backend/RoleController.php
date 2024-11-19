<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PermissionExport;
use App\Http\Controllers\Controller;
use App\Imports\PermissionImport;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RoleController extends Controller
{
    public function AllPermission(){

        $permissions = Permission::orderBy('id', 'asc')->get();
        return view('backend.permission.all_permission',compact('permissions'));

    } // End Method 

    public function AddPermission(){

        return view('backend.permission.add_permission');

    } // End Method 

    public function StorePermission(Request $request){

        $permission = Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

          $notification = array(
            'message' => 'Permission Create Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('add.permission')->with($notification);

    }// End Method 

    public function EditPermission($id){

        $permission = Permission::findOrFail($id);
        return view('backend.permission.edit_permission',compact('permission'));

    }// End Method 


       public function UpdatePermission(Request $request){

        $per_id = $request->id;

        Permission::findOrFail($per_id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

          $notification = array(
            'message' => 'Permission Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.permission')->with($notification);

    }// End Method 


    public function DeletePermission($id){

        Permission::findOrFail($id)->delete();

          $notification = array(
            'message' => 'Permission Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method 

    public function ImportPermission(){

        return view('backend.permission.import_permission');

    }// End Method 

    public function ExportPermission(){

        return Excel::download(new PermissionExport, 'permission.xlsx');
    }// End Method

    public function ImportStore(Request $request){
        Excel::import(new PermissionImport, $request->file('import_file'));
       
        $notification = array(
            'message' => 'Permission Imported Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 
    }// End Method



    // -----------------------ROLES----------------------------
    public function AllRoles(){

        $roles = Role::latest()->get();
        return view('backend.roles.all_role',compact('roles'));

    }// End Method 



    public function AddRoles(){
        return view('backend.roles.add_role');
    }// End Method 

     public function StoreRoles(Request $request){

        Role::create([
            'name' => $request->name, 
        ]);

          $notification = array(
            'message' => 'Role Create Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.roles')->with($notification);

    }// End Method 

    public function EditRoles($id){

        $roles = Role::findOrFail($id);
        return view('backend.roles.edit_role',compact('roles'));
    }// End Method 


     public function UpdateRoles(Request $request){

        $role_id = $request->id;

        Role::findOrFail($role_id)->update([
            'name' => $request->name, 
        ]);

          $notification = array(
            'message' => 'Role Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.roles')->with($notification);

    }// End Method 


    public function DeleteRoles($id){

        Role::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Role Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method 


    /////////// Add Role Permission all Method ////////////


    public function AddRolesPermission(){

        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('backend.roleSetup.add_roles_permission',compact('roles','permissions','permission_groups'));

    }// End Method 

    public function RolePermissionStore(Request $request){

        $data = array();
        $permissions = $request->permission;

        foreach($permissions as $key => $item){
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;

            DB::table('role_has_permissions')->insert($data);

        } // end foreach 

        $notification = array(
            'message' => 'Role Permission Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.roles.permission')->with($notification);

    }// End Method 


    public function AllRolesPermission(){

        $roles = Role::all();
        return view('backend.roleSetup.all_roles_permission',compact('roles'));

    }// End Method 

    public function AdminEditRoles($id){

        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('backend.roleSetup.edit_roles_permission',compact('role','permissions','permission_groups'));

    }// End Method 

    public function AdminRolesUpdate(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = $request->permission;
    
        if (!empty($permissions)) {
            // Sync permissions by name
            $role->syncPermissions($permissions);
        } else {
            // If no permissions are selected, remove all permissions
            $role->syncPermissions([]);
        }
    
        $notification = [
            'message' => 'Role Permission Updated Successfully',
            'alert-type' => 'success',
        ];
    
        return redirect()->route('all.roles.permission')->with($notification);
    }

    public function AdminDeleteRoles($id){

        $role = Role::findOrFail($id);
        if (!is_null($role)) {
            $role->delete();
        }

        $notification = array(
            'message' => 'Role Permission Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method



}
