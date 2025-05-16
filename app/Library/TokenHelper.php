<?php

namespace App\Library;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Crypt;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

use App\Library\Helper as LibHelper;
use App\Models\Documents\Document;
use App\Models\User\User;
use App\Models\Token\TokenSignatureInitial;


class TokenHelper {
    public static function createSignToken(object|array $data, int $addDays = 3) {
        if (gettype($data) == 'array') {
            $data = (object) $data;
        }
                
        try {
            if (!property_exists($data, 'id_document') || $data->id_document == '' || !Document::where('id_document', '=', $data->id_document)->exists()) {
                throw new \Exception('Key id document does not exist');
            }
            
            if (!property_exists($data, 'id_user') || $data->id_user == '' || !User::where('id_user', '=', $data->id_user)->exists()) {
                throw new \Exception('Key id user does not exist');
            }
            
            if (!property_exists($data, 'type_sign') || $data->type_sign == '') {
                throw new \Exception('Key type sign does not exist');
            }
            
            self::checkUsedSignToken($data->id_document, $data->id_user);
            
            $uuidTokenSignatureInitial = LibHelper::generateUniqueUuId('v7', 'id_token_signature_initial', TokenSignatureInitial::class);
            $tokenVal = LibHelper::generateUniqueString(64, 'token', TokenSignatureInitial::class);
            $dataCreate = [
                'id_token_signature_initial' => $uuidTokenSignatureInitial,
                'id_document' => $data->id_document,
                'id_user' => $data->id_user,
                'token' => $tokenVal,
                'type_sign' => $data->type_sign,
                'expired_at' => Carbon::now()->addDays($addDays),
            ];
            $createTokenSignIniti = TokenSignatureInitial::create($dataCreate);
            
            if (!$createTokenSignIniti) {
                throw new \Exception('Unable to create new token');
            }
            
            $result = (object) [
                'status' => true,
                'message' => 'Success create new token',
                'data' => (object) [
                    // 'id_token' => base64_encode($uuidTokenSignatureInitial),
                    // 'token' => base64_encode($tokenVal),
                    'id_token' => $uuidTokenSignatureInitial,
                    'token' => $tokenVal,
                ],
            ];
            
            return $result;
            
        } catch (\Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    public static function checkIntegrateToken($token, $auth_userId) {
        try {
            // $tokenDecode = base64_decode($token);
            // $modTokenSigInit = TokenSignatureInitial::where('token', '=', $tokenDecode)
            $modTokenSigInit = TokenSignatureInitial::where('token', '=', $token)
                ->where('expired_at', '>', Carbon::now())
                ->where('is_used', '=', false);
                
            if (!$modTokenSigInit->exists()) {
                throw new \Exception('Invalid token signature.');
            }
            
            $dataToken = $modTokenSigInit->first();
            if ($dataToken->id_user != $auth_userId) {
                throw new \Exception('Invalid token signature.');
            }
            
            $dataResponse = (object) [
                'status' => true,
                'data' => (object) [
                    'id_document' => $dataToken->id_document,
                    'type_sign' => $dataToken->type_sign,
                ],
            ];
            
            return $dataResponse;
            
        } catch (\Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    public static function getTokenDataSignatureInitial($token, $id_user) {
        try {
            $checkToken = self::checkTokenUserSignatureInitial($token, $id_user);
            if (!$checkToken->status) {
                throw new \Exception($checkToken->message);
            }
            
            $dataToken = TokenSignatureInitial::where('token', '=', $token)->first();
            
            return (object) [
                'status' => true,
                'message' => $checkToken->message,
                'data' => (object) [
                    'id_document' => $dataToken->id_document,
                    'type_sign' => $dataToken->type_sign,
                ],
            ];
            
        } catch (\Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    public static function checkTokenUserSignatureInitial($token, $id_user) {
        try {
            if (!User::where('id_user', '=', $id_user)->exists()) {
                throw new \Exception("The specified user could not be found. Please verify your user credentials.");
            }
            
            $modTokenSigInit = TokenSignatureInitial::where('token', '=', $token);
            $dataModTokenSigInit = $modTokenSigInit->first();
            if (!$modTokenSigInit->exists()) {
                throw new \Exception("The specified token does not exist or is invalid.");
            }
            if (!$modTokenSigInit->where('expired_at', '>', Carbon::now())->exists()) {
                throw new \Exception("The token has expired and is no longer valid.");
            }
            
            if (! ($dataModTokenSigInit->id_user == $id_user)) {
                throw new \Exception("The token is not linked to the specified user.");
            }
            if ($dataModTokenSigInit->is_used) {
                throw new \Exception("The token has already been used. Please request a new token to proceed.");
                // throw new \Exception("The token has been activated or used.");
            }
            
            return (object) [
                'status' => true,
                'message' => 'The token and user have been successfully verified. Access is granted.',
            ];
            
        } catch (\Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    private static function checkUsedSignToken($id_document, $id_user) {
        $modTokenSigInit = TokenSignatureInitial::where('id_document', '=', $id_document)
            ->where('id_user', '=', $id_user)
            ->where('is_used', '=', false);
            
        if ($modTokenSigInit->exists()) {
            $modTokenSigInit->update([
                'is_used' => true
            ]);
        }
    }
}