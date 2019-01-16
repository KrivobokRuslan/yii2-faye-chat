<?php

namespace krivobokruslan\fayechat\repositories;

use krivobokruslan\fayechat\entities\Dialog;

class DialogRepository
{
    public function save(Dialog $dialog)
    {
        if (!$dialog->save()) {
            throw new \DomainException('Saving error');
        }
    }

    public function findByUsers($currentUserId, $userId): ?Dialog
    {
        return Dialog::find()->where(['or', ['and', ['user_id_one' => $currentUserId], ['user_id_two' => $userId]], ['and', ['user_id_one' => $userId], ['user_id_two' => $currentUserId]]])->one();
    }
}