<?php namespace App\Services;
use App\Models\LanguageSettingsModel;
use App\Models\LanguageModel;

class LanguageService{

	public static function getDefaultLanguage()
    {
        $setting = LanguageSettingsModel::first();
        if(empty($setting)) return 'en';

        $language = LanguageModel::find($setting->defaultLanguage);
        return empty($language) ? 'en' : $language->languageCode;
    }
}