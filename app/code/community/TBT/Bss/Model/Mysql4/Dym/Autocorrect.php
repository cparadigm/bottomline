<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the BETTER STORE SEARCH
 * License, which is available at this URL: http://www.betterstoresearch.com/docs/bss_license.txt
 *
 * DISCLAIMER
 * By adding to, editing, or in any way modifying this code, WDCA is not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code. By adding to, editing, or in any way modifying this code, the Licensee terminates any agreement of support
 * offered by WDCA, outlined in the provided Sweet Tooth License.  Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time WDCA spent  during the support process.
 * WDCA does not guarantee compatibility with any other framework extension. WDCA is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension. If you did not receive a copy of the license, please send an email to
 * contact@wdca.ca or call 1-888-699-WDCA(9322), so we can send you a copy immediately.
 *
 * @category   [TBT]
 * @package    [TBT_Bss]
 * @copyright  Copyright (c) 2011 WDCA (http://www.wdca.ca)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
*/

/**
 *
 * @category   TBT
 * @author     WDCA Sweet Tooth Team <contact@wdca.ca>
 */
class TBT_Bss_Model_Mysql4_Dym_Autocorrect extends TBT_Bss_Model_Mysql4_Dym_Abstract
{
    protected function _construct()
    {
        return $this;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $query
     * @return unknown
     */
    public function findPhraseBySoundex($query)
    {
        $query_parts = explode(' ', $query);
        $new_query = $query;

        $cf_t = $this->_getBssIndexTable();
        $conn = $this->getConnection();

        $partial_soundex_parts = array();
        foreach ($query_parts as $qp) {
            if(trim($qp) == "") continue;
            $partial_soundex_parts[] = $this->_sqlSoundex($conn, $qp);
        }
        //echo "<BR /><PRE>Beginning query: {$query} <BR />";


        //echo "<BR />Partial Parts: <BR />";
        //print_r($partial_soundex_parts);
        //echo "<PRE>";

        $all_word_matches = $this->_getWordMatches($conn, $query_parts, $partial_soundex_parts);
        /*
            if(sizeof($possible_words) > 0) {
                $new_query = str_replace($query_parts[$query_part_index], $possible_words[0]['word'], $new_query);
            }
            */
        //echo "<BR />All Word matches for {$query}: <BR />";
        //print_r($all_word_matches);

        $new_query = $this->_getMostProbablePhrase($query, $all_word_matches);
        //echo "<BR />NEW query: {$new_query} <BR />";
        //echo "</PRE>";

        return $new_query;
    }

    protected function _sqlSoundex($conn, $str)
    {
        $sql = $conn->quoteInto(" SELECT SOUNDEX(?); ", $str);
        $soundex_str = $conn->fetchOne($sql);

        return $soundex_str;
    }

    /**
     *
     * @param string $original_query
     * @param string $all_word_matches
     */
    protected function _getMostProbablePhrase($original_query, $phrase_word_matches)
    {
        $new_query = array();

        //$likely_product_id = $this->_getMostLikelyProduct($phrase_word_matches);
        //echo "probably pid={$likely_product_id}";
        foreach($phrase_word_matches as $word_matches) {
            if(empty($word_matches)) continue; // no match

            $use_match = $word_matches[0];
            if(empty($use_match)) continue;

            // saving this becuase we can modify the algorithm to select the best match product phrase
            $word = $use_match['word'];

            if(empty($word)) continue;

            $new_query[] = $word;
        }
        $new_query_phrase = implode(" ", $new_query);

        // If the phrases are the same dont return a result.
        if($this->_areSameSearchPhrases($original_query, $new_query_phrase)) {
            return "";
        }


        return $new_query_phrase;
    }

    /**
     * Checks to see if two search phrases are almost the same
     *
     * @param string $original_query
     * @param string $new_query_phrase
     * @return boolean
     */
    protected function _areSameSearchPhrases($original_query, $new_query_phrase)
    {
        $exact_same = strtolower($original_query) == strtolower($new_query_phrase);
        return $exact_same;
    }

    /**
     * @deprecated used with _getMostLikelyProduct only
     * @param array $word_matches
     * @param integer $likely_product_id
     * @return array
     */
    protected function _getChosenWordMatch($word_matches, $likely_product_id)
    {
        $use_match = null;
        foreach($word_matches as $word_match) {
            if($likely_product_id == $word_match['product_id']) {
                $use_match = $word_match;
                break;
            }
        }
        return $use_match;
    }

    /**
     * @deprecated Deprecated because we need to choose the most likely product based on the
     * whole fulltext search index not only the name.  It only searches name right now.
     *
     * @param array $phrase_word_matches
     * @return integer product id
     */
    protected function _getMostLikelyProduct($phrase_word_matches)
    {
        $pid_map = array();

        // first count all the occurences of product ids
        foreach($phrase_word_matches as $word_matches) {
            if(empty($word_matches)) continue; // no match
            foreach($word_matches as $word_match) {
                if(!isset($pid_map[$word_match['product_id']]))    $pid_map[$word_match['product_id']]= 0;
                $pid_map[$word_match['product_id']]++;
            }
        }
        print_r($pid_map);
        // find the highest occuring product id
        $best_entry = null;
        foreach($pid_map as $pid => $count) {
            if($best_entry == null) {
                $best_entry = array('product_id' => $pid, 'count' => $pid_map[$pid]);
            } else if($pid_map[$pid] > $best_entry['count']) {
                $best_entry = array('product_id' => $pid, 'count' => $pid_map[$pid]);
            }
        }
        return $best_entry['product_id'];
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $conn
     * @param unknown_type $query_parts
     * @param unknown_type $partial_soundex_parts
     * @return unknown
     */
    protected function _getWordMatches($conn, $query_parts, $partial_soundex_parts)
    {
        $all_possible_words = array();

        $naid = $this->_getProductNameId();
        $cpev_t = $this->_getCPEVTable();
        $bss_t = $this->_getBssIndexTable();
        $soundex_match_limit = $this->_getSoundexMatchLimit();

        foreach($partial_soundex_parts as $query_part_index => $partial_soundex_part) {
            $sql = $conn->quoteInto("
                SELECT DISTINCT bi.pns, bi.product_id, IF(names0 .value IS NULL, names2.value, names0 .value) as product_name
                FROM {$bss_t} bi
                LEFT JOIN  `{$cpev_t}` names0 ON ( bi.product_id= names0 .entity_id AND names0 .store_id = bi.store_id AND names0.attribute_id = {$naid} )
                LEFT JOIN  `{$cpev_t}` names2 ON ( bi.product_id= names2.entity_id AND names2.store_id = 0 AND names2.attribute_id = {$naid})
                WHERE bi.pns LIKE CONCAT('%', ? ,'%')
                LIMIT {$soundex_match_limit}
            ", $partial_soundex_part);
            $word_matches = $conn->fetchAll($sql);

            // echo "<BR />Soundex {$partial_soundex_part} matches for {$query_parts[$query_part_index]}: <BR />";
            // print_r($word_matches);


            // Step #1: Find all possible word matches for the word soundex
            $possible_words = $this->_getWordsForMatches($word_matches, $partial_soundex_part, $query_parts[$query_part_index]);

            // Step #2: Use the most

            // echo "<BR />Possible words for {$query_parts[$query_part_index]}: <BR />";
            $all_possible_words[] = $possible_words;

        }

        return $all_possible_words;
    }


    /**
     * Enter description here...
     *
     * @param unknown_type $word_matches
     * @return unknown
     */
    protected function _getWordsForMatches($word_matches, $partial_soundex_part, $query_part)
    {
        $possible_words = array();

        // echo "query_part:"; print_r($query_part);
        // echo "word_matches:"; print_r($word_matches);
        // echo "partial_soundex_part:"; print_r($partial_soundex_part);

        // #1 Find and weigh all words
        foreach($word_matches as $word_match) {
            $word_parts = explode(' ', $word_match['product_name']);

            $pns_match_search_index = empty($partial_soundex_part) ? 0 : strpos($word_match['pns'], $partial_soundex_part);

            $pns_match_subsection = substr($word_match['pns'], 0, $pns_match_search_index+strlen($partial_soundex_part));
            //echo "pns_match_subsection:{$pns_match_subsection};" . "\n";

            $pns_match_count =  substr_count($pns_match_subsection, "|");
            //echo "pns_match_count:{$pns_match_count};" . "\n";

            if(!isset($word_parts[$pns_match_count])) {
                continue; // We didn't find the word in the phrase's soundex key (ie PNS), so just continue on. We shouldn't hit here pretty much ever so it's more of a failsafe.
            }

            $word = $word_parts[$pns_match_count];

            if(isset($possible_words[$word])) {
                continue; // we've already ranked this word.
            }

            // Rank the word using levenshtein
            $possible_words[$word] = array('word'=>$word, 'weight'=>0, 'product_id' => $word_match['product_id']);
            $possible_words[$word]['weight'] = $this->_getLevenschteinRank($query_part, $word);
        }

        //echo "Possible workds:"; print_r($possible_words);
        // #2 Create a priority array
        $possible_words = $this->_getSortedByWeight($possible_words);

        return $possible_words;
    }

    /**
     * Returns back a rank with Levenschtein. A '0' indicates that all letters were off, and a number equal to
     * the length of the query string indicates that all letters were the same.
     * Algorithm is case incensitive
     * @param unknown_type $query
     * @param unknown_type $challenger
     */
    protected function _getLevenschteinRank($query, $challenger)
    {
        $levenshtein_distance = levenshtein( strtolower($query), strtolower($challenger) );
        $levenshtein_rank = max(0, strlen($query) - $levenshtein_distance);

        //echo "levenshtein rank between {$query} and {$challenger} is {$levenshtein_rank}.\n";
        return $levenshtein_rank;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $possible_words
     */
    protected function _getSortedByWeight($possible_words)
    {
        $possible_words = array_values($possible_words);

        $words = array();
        foreach ($possible_words as $key => $row) {
            $words[$key]  = $row['weight'];
            // of course, replace 0 with whatever is the date field's index
        }

        array_multisort($words, SORT_DESC, $possible_words);

        return $possible_words;
    }


    protected function _getSoundexMatchLimit()
    {
        return 50;
    }


}
