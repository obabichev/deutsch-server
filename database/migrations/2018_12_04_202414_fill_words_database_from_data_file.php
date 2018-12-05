<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillWordsDatabaseFromDataFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->fillDataBaseFromFile('./data/de-en.data');
    }

    protected function fillDataBaseFromFile($file)
    {
        $handle = fopen($file, "r");

        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $parsed = $this->parseLine($line);
                if ($parsed) {
                    $word = \App\Word::create($parsed['word']);

                    foreach ($parsed['tags'] as $name) {
                        $tag = \App\Tag::firstOrCreate(['name' => $name]);
                        $word->tags()->attach($tag->id);
                    }
                }
            }

            fclose($handle);
        } else {
            print "Can not open file\n";
        }
    }

    protected function parseLine($line)
    {
        $pattern = '/^(\(([\w\däüöÄÜÖß\-\ ]+)\))?( )?([\w\däüöÄÜÖß\-\ ]+)( )?(\{(f|m|n|pl)\})?( \[([\w\däüöÄÜÖß\-]+)\])?\t(\(([\w\däüöÄÜÖß\-\ ]+)\))?( )?([\w\däüöÄÜÖß \-\ ]+)( \[([\w\däüöÄÜÖß\-\ ]+)\])?(\t([\w]+))?(\t(((\[[\w.]+\])( )?)*))?$/';

        $check = preg_match($pattern, $line, $matches);
        if ($check) {
            $word = [
                'val_pre' => $this->getMatchedOrNull($matches, 2),
                'val' => $this->getMatchedOrNull($matches, 4),
                'val_post' => $this->getMatchedOrNull($matches, 9),
                'gender' => $this->getMatchedOrNull($matches, 7),
                'tr_pre' => $this->getMatchedOrNull($matches, 11),
                'tr' => $this->getMatchedOrNull($matches, 13),
                'tr_post' => $this->getMatchedOrNull($matches, 15),
                'type' => $this->getMatchedOrNull($matches, 17),
            ];
            $tags = [];
            if (isset($matches[19])) {
                $tags = $this->parseTags($matches[19]);
            }

            return [
                'word' => $word,
                'tags' => $tags
            ];
        } else {
            return null;
        }
    }

    protected function parseTags($tags)
    {
        if (empty($tags)) {
            return [];
        }

        $tagPattern = '/[\w\d]+/';
        $matches = [];

        $check = preg_match_all($tagPattern, $tags, $matches);

        if ($check) {
            return $matches[0];
        } else {
            return [];
        }
    }

    protected function getMatchedOrNull($matches, $index)
    {
        if (!isset($matches[$index])) {
            return null;
        }

        $result = trim($matches[$index]);
        if (!empty($result)) {
            return $result;
        } else {
            return null;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Word::truncate();
        \App\Tag::truncate();
    }
}
