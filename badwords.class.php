<?php
class DFA
{
    private $arrHashMap = [];
    protected $badwords;

    public function __construct($bdwarr)
    {
    	//对敏感词的批量导入，基于Laravel的DB facades
        // $this->badwords = DB::table('badwords')->get()->map(function($item){
        //     return $item->badword;
        // });
        $this->badwords = $bdwarr;
        $this->addKeyWords($this->badwords);
    }

    public function addKeyWords($badwords)
    {
        foreach($badwords as $value)
        {
            $this->addKeyWord($value);
        }
    }
    public function getHashMap() {
        print_r($this->arrHashMap);
    }

    public function addKeyWord($strWord) {
        $len = mb_strlen($strWord, 'UTF-8');

        // 传址
        $arrHashMap = &$this->arrHashMap;
        for ($i=0; $i < $len; $i++) {
            $word = mb_substr($strWord, $i, 1, 'UTF-8');
            // 已存在
            if (isset($arrHashMap[$word])) {
                if ($i == ($len - 1)) {
                    $arrHashMap[$word]['end'] = 1;
                }
            } else {
                // 不存在
                if ($i == ($len - 1)) {
                    $arrHashMap[$word] = [];
                    $arrHashMap[$word]['end'] = 1;
                } else {
                    $arrHashMap[$word] = [];
                    $arrHashMap[$word]['end'] = 0;
                }
            }
            $arrHashMap = &$arrHashMap[$word];
        }
    }
    //查找敏感词
    public function searchKey($strWord) {
        $len = mb_strlen($strWord, 'UTF-8');
        $arrHashMap = $this->arrHashMap;
        $badword = '';
        for ($i=0; $i < $len; $i++) {
            $word = mb_substr($strWord, $i, 1, 'UTF-8');
            if (!isset($arrHashMap[$word])) {
                // reset hashmap
                $arrHashMap = $this->arrHashMap;
                $badword = '';
                continue;
            }
            $badword .= $word;
            if ($arrHashMap[$word]['end']) {
                //这里可以不用return，存入badword继续判断敏感词。
                return ['status' =>true,'badword' => $badword];
            }
            $arrHashMap = $arrHashMap[$word];
        }
        return ['status' =>false,'badword' => null];
    }
}