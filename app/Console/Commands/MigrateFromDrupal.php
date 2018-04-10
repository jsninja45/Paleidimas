<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Review;

class MigrateFromDrupal extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'migrate-drupal';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $drupal = DB::connection('drupal_mysql');

        // migrate users
        $drupal->table('users')->chunk(100, function($users) use ($drupal) {
            foreach ($users as $user) {
                if (empty($user->name) AND empty($user->email)) continue;

                $newUser = new User;
                $newUser->name = $user->name;
                $newUser->old_password = $user->pass;
                $newUser->email = $user->mail;
                $newUser->created_at = $user->created;
                $newUser->deleted = ($user->status) ? 0 : 1;
                $newUser->full_discount = true;
                $newUser->old_id = $user->uid;

                // OAuth data
                $oauth = $drupal->table('hybridauth_identity')->select('provider', 'provider_identifier')
                    ->where('uid', $user->uid)->first();

                if (!empty($oauth)) {
                    $newUser->social_login_type = strtolower($oauth->provider);
                    $newUser->social_login_id = $oauth->provider_identifier;
                }

                $newUser->save();

                // Assign roles
                $roles = $drupal->table('users_roles')->where('uid', $user->uid)->lists('rid');

                if (in_array(3, $roles) OR in_array(6, $roles)) { // admin
                    $newUser->addRole('admin');
                    $newUser->addRole('subtitler');
                    $newUser->addRole('editor');
                    $newUser->addRole('transcriber');
                    $newUser->addRole('client');
                }

                if (in_array(4, $roles)) { // transcriber
                    $newUser->addRole('transcriber');
                    $newUser->addRole('client');
                }

                if (in_array(7, $roles)) { // editor
                    $newUser->addRole('editor');
                    $newUser->addRole('client');
                }

                if (in_array(5, $roles)) { // client
                    $newUser->addRole('client');
                }

                // Assign languages for transcribers and editors
                $possibleLanguages = $this->getPossibleLanguages();
                $languages = [];

                if ($newUser->hasRole('transcriber')) {
                    $languages = unserialize($drupal->table('speech2txt_transcriber_ratio')
                        ->where('uid', $user->uid)->pluck('languages'));
                } else if ($newUser->hasRole('editor')) {
                    $languages = unserialize($drupal->table('speech2txt_editor_ratio')
                        ->where('uid', $user->uid)->pluck('languages'));
                }

                if (is_array($languages)) {
                    foreach ($languages as $language) {
                        if (!is_int($language) && $language != 13) {
                            $languageId = $possibleLanguages[$language];
                            $newUser->languages()->attach($languageId);
                        }
                    }
                }
            }
        });

        // migrate feedback
        $feedback = $drupal->table('s2t_feedback')->where('aproved', 1)->get();

        foreach ($feedback as $review) {
            $newReview = new Review;
            $newReview->created_at = $review->created;
            $newReview->name = $review->name;
            $newReview->content = $review->feedback;
            $newReview->rating = $review->rating;
            $newReview->save();
        }
    }

    protected function getPossibleLanguages()
    {
        $languages = array(
            0 => 1,
            1 => 2,
            2 => 3,
            3 => 4,
            4 => 5,
            5 => 6,
            6 => 7,
            7 => 8,
            8 => 9,
            9 => 10,
            10 => 11,
            11 => 12,
            12 => 13,
//        13 => t('Tagalog'), // removed
            14 => 14,
            15 => 15,
            16 => 16,
            17 => 17,
            18 => 18,
            19 => 19,
            20 => 20,
            21 => 21,
            22 => 22,
            23 => 23,
            24 => 24
        );

        asort($languages);

        return $languages;
    }

}
