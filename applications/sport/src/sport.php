<?php

declare(strict_types=1);

namespace application\sport;

use framework\template\templateInterface;

class sport implements sportInterface
{

    private $template;

    private $config;


    public function __construct(templateInterface $template, array $config)
    {
        $this->template     = $template;
        $this->config       = $config;
    }

    private function getSportData(): array
    {
        $csv  = $this->config['apiUrl'];

        if (false === $handle = fopen($csv, 'r')){
            trigger_error('The CSV file cannot be opened', E_USER_WARNING);
            return array();
        }

        $header = array();
        $scores = array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            if(!$header){
                $header = $data;
                continue;
            }

            $data   = array_combine($header, $data);
            $HG     = $data['HomeTeam'];
            $AG     = $data['AwayTeam'];
            $FT_HG  = (int)$data['FTHG'];
            $FT_AG  = (int)$data['FTAG'];

            if($FT_AG == $FT_HG) {
                continue;
            }else if($FT_AG > $FT_HG){

                $win    = $AG;
                $lose   = $HG;
            }else{
                $win    = $HG;
                $lose   = $AG;
            }

            if(!isset($scores[$win]))
                $scores[$win]    = Array($lose);
            else if(!in_array($lose, $scores[$win]))
                $scores[$win][]  = $lose;
        }
        fclose($handle);


        return $scores;
    }

    public function drawPage(): string
    {

        $data = $this->getSportData();
        return $this->template->render(dirname(__DIR__) . '/template/sport.php', array('teams' => $data));

    }

    public function drawThumbnail(): string
    {

        $data       = $this->getSportData();
        $team       = key($data);
        return $this->template->render(dirname(__DIR__) . '/template/thumbnail.php', array('team' => $team));

    }
}
