<?php

namespace App\Utils;

use Illuminate\Support\Str;

use App\Libraries\ArrayHelper;
use App\Libraries\StringHelper;

use App\Utils\ModelUtils;

class UserUtils {
    
    PRIVATE CONST MIN_CREDENTIAL = 2;
    PRIVATE CONST MIN_ACCRONYM = 4;
    
    private const RANDOM_LENGTH = 8;
    
    public function create_username(string $fullname, bool $just_leter = false) {
        $words = explode(' ', $fullname);
        $accronym = collect($words)
            ->implode(fn($v) => ucfirst(mb_substr($v, 0, 1)));
        
        $lengthAccronym = strlen($accronym);
        if ($lengthAccronym < static::MIN_ACCRONYM) {
            $accronym .= StringHelper::random(static::MIN_ACCRONYM - $lengthAccronym, true, false, false);
        }
        
        $model = ModelUtils::createInstanceModel('user');
        $username = $accronym . '_';
        
        if ($just_leter) {
            $username .= StringHelper::random(static::RANDOM_LENGTH - $lengthAccronym, true, false, false);
        } else {
            $username .= Str::random(static::RANDOM_LENGTH);
        }
        
        while($model->query()->where('username', '=', $username)->exists()) {
            if ($just_leter) {
                $username .= StringHelper::random(1 - $lengthAccronym, true, false, false);
            } else {
                $username .= Str::random(1);
            }
        }
        
        return $username;
    }
    
}