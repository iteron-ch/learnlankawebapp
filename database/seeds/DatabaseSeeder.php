<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role, App\Models\User, App\Models\Contact, App\Models\Admin, App\Models\School, App\Models\Teacher;
use App\Services\LoremIpsumGenerator;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$lipsum = new LoremIpsumGenerator;

		Role::create([
			'name' => 'admin',
			'display_name' => 'Administrator',
                        'description' => 'Administrator'
		]);

		Role::create([
			'name' => 'school',
			'display_name' => 'school',
                        'description' => 'school'
		]);

		Role::create([
			'name' => 'teacher',
			'display_name' => 'teacher',
                        'description' => 'teacher'
		]);

		User::create([
			'username' => 'admin',
			'email' => 'admin@sats.com',
			'password' => bcrypt('asd123#'),
			'seen' => true,
			'role_id' => 1,
                        'userable_type' => 'Admin',
                        'userable_id' => 1,
			'confirmed' => true
		]);

		User::create([
			'username' => 'school_1',
			'email' => 'school_1@sats.com',
			'password' => bcrypt('asd123#'),
			'seen' => true,
			'role_id' => 2,
                        'userable_type' => 'School',
                        'userable_id' => 1,
			'valid' => true,
			'confirmed' => true
		]);

		User::create([
			'username' => 'school_2',
			'email' => 'school_2@sats.com',
			'password' => bcrypt('asd123#'),
			'role_id' => 2,
                        'userable_type' => 'School',
                        'userable_id' => 2,
			'confirmed' => true
		]);

		User::create([
			'username' => 'teacher_1',
			'email' => 'teacher_1@sats.com',
			'password' => bcrypt('asd123#'),
			'role_id' => 3,
                        'userable_type' => 'Teacher',
                        'userable_id' => 3,
			'confirmed' => true
		]);
                
                Admin::create([
			'admin_code' => 'ad_000001'
		]);

		School::create([
			'school_code' => 'sc_000001'
		]);
                
                School::create([
			'school_code' => 'sc_000002'
		]);
                
		Teacher::create([
			'teacher_code' => 'te_000001'
		]);

		Contact::create([
			'name' => 'Dupont',
			'email' => 'dupont@la.fr',
			'text' => 'Lorem ipsum inceptos malesuada leo fusce tortor sociosqu semper, facilisis semper class tempus faucibus tristique duis eros, cubilia quisque habitasse aliquam fringilla orci non. Vel laoreet dolor enim justo facilisis neque accumsan, in ad venenatis hac per dictumst nulla ligula, donec mollis massa porttitor ullamcorper risus. Eu platea fringilla, habitasse.'
		]);

		Contact::create([
			'name' => 'Durand',
			'email' => 'durand@la.fr',
			'text' => ' Lorem ipsum erat non elit ultrices placerat, netus metus feugiat non conubia fusce porttitor, sociosqu diam commodo metus in. Himenaeos vitae aptent consequat luctus purus eleifend enim, sollicitudin eleifend porta malesuada ac class conubia, condimentum mauris facilisis conubia quis scelerisque. Lacinia tempus nullam felis fusce ac potenti netus ornare semper molestie, iaculis fermentum ornare curabitur tincidunt imperdiet scelerisque imperdiet euismod.'
		]);

		Contact::create([
			'name' => 'Martin',
			'email' => 'martin@la.fr',
			'text' => 'Lorem ipsum tempor netus aenean ligula habitant vehicula tempor ultrices, placerat sociosqu ultrices consectetur ullamcorper tincidunt quisque tellus, ante nostra euismod nec suspendisse sem curabitur elit. Malesuada lacus viverra sagittis sit ornare orci, augue nullam adipiscing pulvinar libero aliquam vestibulum, platea cursus pellentesque leo dui. Lectus curabitur euismod ad, erat.',
			'seen' => true
		]);

	}

}
