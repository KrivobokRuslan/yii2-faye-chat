<?php

namespace krivobokruslan\fayechat\useCases;

use krivobokruslan\fayechat\entities\Dialog;
use krivobokruslan\fayechat\forms\DialogCreateForm;
use krivobokruslan\fayechat\repositories\DialogRepository;

class DialogService
{

    private $dialogs;

    public function __construct(DialogRepository $dialogs)
    {
        $this->dialogs = $dialogs;
    }

    public function create(DialogCreateForm $form, $currentUserId): Dialog
    {
        if (($dialog = $this->dialogs->findByUsers($currentUserId, $form->user_id)) !== null) {
            return $dialog;
        }

        $dialog = Dialog::create($currentUserId, $form->user_id);
        $this->dialogs->save($dialog);
        return $dialog;
    }
}