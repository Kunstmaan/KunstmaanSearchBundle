<?php

namespace Kunstmaan\SearchBundle\Search;


class AnalysisFactory
{
    private $analyzers;
    private $tokenizers;
    private $filters;
    private $stopwords;


    public function __construct()
    {
        $this->analyzers = array();
        $this->tokenizers = array();
        $this->filters = array();
        $this->stopwords = array();
    }

    public function build()
    {
        $analysis = array(
            'analyzer' => $this->analyzers,
            'tokenizer' => $this->tokenizers,
            'filter' => $this->filters
        );

        return $analysis;
    }

    public function addIndexAnalyzer($lang)
    {
        $this->analyzers['index_analyzer_' . $lang] = array(
            'type' => 'custom',
            'tokenizer' => 'whitespace',
            'filter' => array('trim', 'lowercase', 'stopwords_'.$lang, 'asciifolding', 'strip_special_chars', 'ngram')
        );

        //add dependencies
        $this
            ->addStopWordsFilter($lang)
            ->addStripSpecialCharsFilter()
            ->addNGramFilter();

        return $this;
    }

    public function addSuggestionAnalyzer($lang)
    {
        $this->analyzers['suggestion_analyzer_' . $lang] = array(
            'type' => 'custom',
            'tokenizer' => 'whitespace',
            'filter' => array('trim', 'lowercase', 'asciifolding', 'strip_special_chars')
        );

        //add dependencies
        $this
            ->addStripSpecialCharsFilter()
            ->addNGramFilter();

        return $this;
    }

    public function addNGramFilter()
    {
        $this->filters["ngram"] = array(
            "type" => "nGram",
            "min_gram" => 4,
            "max_gram" => 30
        );

        return $this;
    }

    public function addStopWordsFilter($lang, array $words = null)
    {
        if($words == null && isset($this->stopwords[$lang])){
            $words = $this->stopwords[$lang];
        } elseif ($words == null) {
            $words = array();
        }

        $this->filters["stopwords_".$lang] = array(
            "type" => "stop",
            "stopwords" => $words,
            "ignore_case" => true
        );

        return $this;
    }

    public function addStripSpecialCharsFilter()
    {
        $this->filters["strip_special_chars"] = array(
            "type" => "pattern_replace",
            "pattern" => "[^0-9a-zA-Z]",
            "replacement" => ""
        );

        return $this;
    }

    /**
     * @param mixed $stopwords
     */
    public function setStopwords($lang, $stopwords)
    {
        $this->stopwords[$lang] = $stopwords;
    }

    /**
     * @return mixed
     */
    public function getStopwords($lang = 'en')
    {
        return $this->stopwords[$lang];
    }


}