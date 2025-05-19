<?php

namespace App\Helpers;

use App\Models\LogsModel;
use Exception;

class LogsHelper
{
    public static function log(int $userID, int $actionID, $model, int $modelID, $changes)
    {
        try{
            LogsModel::create([
                'user_id' => $userID,
                'action_id' => $actionID,
                'model' => get_class($model),
                'model_id' => $modelID,
                'changes' => $changes,
            ]);
        }catch(Exception $e){
            return response()->json(['message' => 'Failed to update user', 'error' => $e], 500);
        }
    }
}