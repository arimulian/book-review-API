<?php

use App\Models\User;
use function Pest\Laravel\{post,get,patch,delete};

/*
  * User Registration Tests
 */
describe('User Registration Service Test', function() {
    it('User registration success', function() {
        post('api/users/register', [
            'username' => 'ar',
            'password' => 'secret',
            'fullName'=> 'ari',
        ])->assertStatus(201)
                ->assertJson([
                    'data'=> [
                        'fullName' => 'ari',
                        'username' => 'ar',
                    ]
                ]);

    });

    it('User registration fail', function() {
        post('api/users/register', [
            'fullName' => '',
            'username' => '',
            'password' => ''
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    "fullName" => [
                        "The full name field is required."
                    ],
                    "username" => [
                        "The username field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ]]
            ]);
    });

    it('User registration username exists in database', function() {
            post('api/users/register', [
                'fullName' => 'user2',
                'username' => 'user2@test.com',
                'password' => 'secret',
            ]);

            post('api/users/register', [
                'fullName' => 'user2',
                'username' => 'user2@test.com',
                'password' => 'secret',
            ])->assertStatus(400)
                ->assertJson([
                    'errors' => [
                        'username' => 'The username has already been taken'
                    ]
                ]);
        });

});


/*
 * User Login Tests
 */
describe('User Login Service Test', function() {

    it('User Login Successful', function() {
        $response = post('api/users/login', [
            'username' => 'username',
            'password' => 'secret',
        ]);
        $user = User::query()->first();
        $response->assertStatus(200)
                ->assertJson([
                   'data' => [
                       'fullName' => $user->fullName,
                       'username' => $user->username,
                       'token' => $user->token,
                   ]
                ]);
    });

    it('User login is incorrect password', function () {
        post('api/users/login',[
            'username' => 'username',
            'password' => 'wrong',
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'message' => 'username or password is incorrect',
                ]
            ]);
    });

    it('user login username is not registered', function () {
        post('api/users/login',[
            'username' => 'user',
            'password' => 'secret',
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                   'message' => 'username or password is incorrect',
                ]
            ]);
    });
});

/*
 * User Get data current user
 */
describe('User Current Data Tests', function() {

    it('Get User Current Data Successful', function() {
        $user = User::query()->first();
        get('api/users/current',[
            'Authorization' =>  $user->token,
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'fullName' => $user->fullName,
                    'username' => $user->username,
                    'token' => $user->token,
                ]
            ]);
    });

    it('Get User Current Data do not use token', function() {
        get('api/users/current',[
            'Authorization' =>  '',
        ])
            ->assertStatus(401)
            ->assertJson([
                   'message' => 'Unauthorized'
            ]);
    });

    it('Get User Current Data invalid Token', function() {
        get('api/users/current',[
            'Authorization' =>  'invalidToken',
        ])
            ->assertStatus(401)
            ->assertJson([
                   'message' => 'Unauthorized'
            ]);
    });
});

/*
 * User update Tests
 */
describe('User Update Data Tests', function() {

    it('Update fullName user successfully', function() {
        $token = User::query()->first();
        $response = patch('api/users/current',[
            'fullName' => 'user2',
        ],[
            'Authorization' => $token->token
        ]);
        $user = User::query()->first();
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'fullName' => $user->fullName,
                    'username' => $user->username,
                ]
            ]);
    });

    it('Update password user successfully', function() {
        $token = User::query()->first();
        $response = patch('api/users/current',[
            'password' => 'secret',
        ],[
            'Authorization' => $token->token
        ]);
        $user = User::query()->first();
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'fullName' => $user->fullName,
                    'username' => $user->username,
                ]
            ]);
    });

    it('Update user fail', function() {
        $response = patch('api/users/current',[
            'name' => 'user2',
        ],[
            'Authorization' => ''
        ]);
        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthorized'
            ]);
    });

});


/*
 * User Logout Tests
 */
describe('User Logout test', function() {

    it('User logout successfully', function() {
        $user = User::query()->first();
        delete('api/users/logout', headers: [
            'Authorization' => $user->token
        ])->assertStatus(200)
            ->assertJson([
                   'logout' => true
            ]);
    });

    it('User logout do not use token', function() {
        delete('api/users/logout',headers: [
            'Authorization' => ''
        ])->assertStatus(401)
            ->assertJson([
               'message' => 'Unauthorized'
            ]);
    });

    it('User logout invalid token', function() {
        delete('api/users/logout', headers: [
            'Authorization' => 'invalidToken'
        ])->assertStatus(401)
            ->assertJson([
               'message' => 'Unauthorized'
            ]);
    });
});