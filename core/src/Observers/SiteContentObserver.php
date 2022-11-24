<?php namespace EvolutionCMS\Observers;

use EvolutionCMS\Models\SiteContent;

class SiteContentObserver
{
    public function saving(SiteContent $model) : bool
    {
        $model->editedby = evolutionCMS()->getLoginUserID();
        $model->pagetitle = trim($model->pagetitle);

        return !empty($model->pagetitle);
    }
}
