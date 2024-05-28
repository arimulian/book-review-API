<?php

use App\Models\User;
use function Pest\Laravel\{post,get,patch,delete};


describe('User Registration Service Test', function() {
    it('User registration success', function() {
        post('api/users/register', [
            'name'=> 'user2',
            'email' => 'user2@test.com',
            'password' => 'secret',
        ])->assertStatus(201)
                ->assertJson([
                    'data'=> [
                        'name' => 'user2',
                        'email' => 'user2@test.com'
                    ]
                ]);

    });

    it('User registration fail', function() {
            post('api/users/register',[
                'name' => '',
                'email' => '',
                'password' => ''
            ])
                ->assertStatus(400)
                ->assertJson([
                    'errors' => [
                        'name' => ['The name field is required.'],
                        'email' => ['The email field is required.'],
                        'password' => ['The password field is required.']
                    ]
                ]);
        });

    it('User registration email exists in database', function() {
            post('api/users/register', [
                'name' => 'user2',
                'email' => 'user2@test.com',
                'password' => 'secret',
            ]);

            post('api/users/register', [
                'name' => 'user2',
                'email' => 'user2@test.com',
                'password' => 'secret',
            ])->assertStatus(400)
                ->assertJson([
                    'errors' => [
                        'email' => ['The email has already been taken.']
                    ]
                ]);
        });

});



describe('User Login Service Test', function() {

    it('User Login Successful', function() {
        $response = post('api/users/login', [
            'email' => 'user@test.com',
            'password' => 'secret',
        ]);
        $user = User::query()->first();
        $response->assertStatus(200)
                ->assertJson([
                   'data' => [
                       'name' => $user->name,
                       'email' => $user->email,
                       'token' => $user->token,
                   ]
                ]);
    });

    it('User login is incorrect password', function () {
        post('api/users/login',[
            'email' => 'user@example.com',
            'password' => 'wrong',
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'message' => 'email or password is incorrect',
                ]
            ]);
    });

    it('user login email is not registered', function () {
        post('api/users/login',[
            'email' => 'user@test.com',
            'password' => 'wrong',
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                   'message' => 'email or password is incorrect',
                ]
            ]);
    });
});

describe('User Current Data Tests', function() {

    it('Get User Current Data Successful', function() {
        $user = User::query()->first();
        get('api/users/current',[
            'Authorization' =>  $user->token,
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
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

describe('User Update Data Tests', function() {

    it('Update name user successfully', function() {
        $token = User::query()->first();
        $response = patch('api/users/current',[
            'name' => 'user2',
        ],[
            'Authorization' => $token->token
        ]);
        $user = User::query()->first();
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
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
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
    });

    it('Update user fail', function() {
        $token = User::query()->first();
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