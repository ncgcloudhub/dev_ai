<?php

namespace App\Http\Controllers;

use App\Models\CustomTemplate;
use App\Models\DalleImageGenerate;
use App\Models\Session;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\EducationTools;
use App\Models\Expiration;
use App\Models\PromptLibrary;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        logActivity('Dashboard', 'accessed Dashboard');
        $user = Auth::user();
    
        $userId = auth()->id(); // Get the authenticated user's ID
        
        $eduTools = EducationTools::limit(5)->get();
        
            // Append the full Azure URL to each image
            foreach ($eduTools as $image) {
                $image->image = config('filesystems.disks.azure.url') 
                    . config('filesystems.disks.azure.container') 
                    . '/' . $image->image;
            }
        
        $prompts = PromptLibrary::inRandomOrder()
            ->limit(10)->get();
       
        $aiContentCreator = Template::inRandomOrder()
            ->limit(6)->get();

        $expirations = Expiration::orderBy('expires_on', 'asc')->get();


        $notifications = auth()->user()->unreadNotifications()->latest()->take(5)->get();

        return view('admin.admin_dashboard_1', compact('user','eduTools','prompts','aiContentCreator','expirations','notifications'));
    }

    public function showChangePasswordForm(User $user)
    {
        // Pass the user data to the view
        return view('backend.user.user_password', compact('user'));
    }

    public function changeUserPassword(Request $request, User $user)
    {
        // Validate the form data
        $request->validate([
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        // Redirect back to the form with success message
        return redirect()->route('admin.users.changePassword.view', ['user' => $user->id])
            ->with('success', 'Password updated successfully.');
    }

    public function destroy(User $user)
    {
        // Delete the user
        $user->delete();

        // Optionally, you can redirect the user to a specific page after deletion
        return redirect()->route('all.admin')->with('success', 'User deleted successfully.');
    }

    // BULK DELETE
    public function bulkDelete(Request $request) {
    User::whereIn('id', $request->user_ids)->delete();
    return redirect()->back()->with('success', 'Selected users deleted successfully.');
}

public function bulkBlock(Request $request) {
    $users = User::whereIn('id', $request->user_ids)->get();
    foreach ($users as $user) {
        $user->block = !$user->block; // Toggle block status
        $user->save();
    }
    return redirect()->back()->with('success', 'Block status updated for selected users.');
}

public function bulkStatusChange(Request $request) {
    User::whereIn('id', $request->user_ids)->update(['status' => 'inactive']); // Example: Set to 'inactive'
    return redirect()->back()->with('success', 'Status changed for selected users.');
}


    // Add Admin
    public function AllAdmin()
    {

        $alladmin = User::where('role', 'admin')->get();
        return view('admin.all_admin', compact('alladmin'));
    } // End Method 

    public function AddAdmin()
    {

        $roles = Role::all();
        return view('admin.add_admin', compact('roles'));
    } // End Method 


    public function StoreAdmin(Request $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role = 'admin'; // Make sure you have this column in your users table
        $user->status = 'active';
        $user->save();

        if ($request->roles) {
            // Fetch the role by ID and assign it to the user
            $role = Role::findById($request->roles);
            if ($role) {
                $user->assignRole($role);

                // Automatically assign permissions from the role to the user
                // Spatie Permission will automatically sync the permissions
                $user->syncPermissions($role->permissions); // Sync permissions based on the assigned role
                
            }
        }

        $notification = [
            'message' => 'New Admin User Inserted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.admin')->with($notification);
    }

    public function EditAdmin($id)
    {

        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.edit_admin', compact('user', 'roles'));
    } // End Method

    public function UpdateAdmin(Request $request, $id)
    {

        $user = User::findOrFail($id);
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->role = 'admin';
        $user->status = 'active';
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            // Fetch the role by ID and assign it to the user
            $role = Role::findById($request->roles);
            if ($role) {
                $user->assignRole($role);

                $user->syncPermissions($role->permissions); // Sync permissions based on the assigned role
            }
        }

        $notification = array(
            'message' => 'New Admin User Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.admin')->with($notification);
    } // End Method 


}
