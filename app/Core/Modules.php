<?php


namespace App\Core;

class Modules
{


    /**
     * plugin meta data
     *
     */
    var $plugin = array();
    public $errors = array();

    private function __construct()
    {

    }

    /**
     * Returns a Singleton instance of this class.
     *
     * @return Modules
     */
    public static function getInstance(): self
    {
        static $instance;

        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Prevent the object from being cloned.
     */
    protected function __clone()
    {
    }

    /**
     * Avoid serialization.
     */
    public function __wakeup()
    {
    }

    /**
     * Reads in .php plugin meta data from given directory and one level subdirectory.
     * The data gets stored into ->$plugin for later use, augmented by every
     * plugins [fn] relative to the supplied basedir.
     * @param string $basedir
     * @return array
     */
    public function scan($basedir = MODULES_PATH)
    {
        #-- each file
        foreach ($this->scan_subdirs($basedir) as $num => $fn) {
            #-- parse
            if ($e = $this->read($fn)) {
                $file_pat = $fn;
                #-- has plugin custom set id?
                if (empty($e["id"])) {
                    $this->error("Plugin '{$e['title']}' has no ID specified\n");
                } else {
                    $id = $e["id"];
                }

                #-- add localized filename
                $fn = substr($fn, strlen($basedir) + 1);
                $pat = explode($basedir . "/", $file_pat)[1];
                //fn is short for File Name
                $e["fn"] = $pat;

                #-- append to list
                if (isset($this->plugin[$id])) {
                    $this->error("Plugin '{$e['title']}' is already registered\n");
                } else {
                    $this->plugin[$id] = $e;
                }
            }
        }

        // send it back even if probably unused
        return $this->plugin;
    }

    /**
     * look for .php files in subdirectories
     *
     */
    private function scan_subdirs($basedir)
    {
        $r = array();
        if ($dh = opendir($basedir)) {
            while ($fn = readdir($dh)) {
                if ($fn[0] == ".") {   // skip hidden files

                } elseif (is_dir("$basedir/$fn")) {    // subdir
                    if ($ll = opendir("$basedir/$fn")) {
                        while ($last_level = readdir($ll)) {
                            if ($last_level[0] != "." && !is_dir($last_level)) {
                                if (strpos($last_level, ".php")) {
                                    $r[] = "$basedir/$fn/$last_level";
                                }
                            }

                        }
                    }
                    closedir($ll);
                } elseif (strpos($fn, ".php")) {   // add if .php script
                    $r[] = "$basedir/$fn";
                }
            }
            closedir($dh);
        }
        return $r;
    }

    private function read($fn, $size = 4096)
    {
        if (file_exists($fn) and ($f = fopen($fn, "r"))) {
            $src = fread($f, $size);
            fclose($f);
            $src .= "\n*/?>";
            return $this->parse($src);
        } else {
            //File does not exist, or unreadable
            return null;
        }
    }

    //~ /**
    //~ * plugin basename
    //~ * e.g. "filename.inc.php" -> "filename"
    //~ *
    //~ */
    //~ function basename($fn, $cut_extensions=array(".php", ".php5", ".inc", ".cls", ".class")) {
    //~ foreach ($cut_extensions as $ext) {
    //~ $fn = basename($fn, $ext);
    //~ }
    //~ return $fn;
    //~ }

    private function parse($src)
    {
        $info = array();

        #-- first comment block
        $src = $this->extract_first_comment($src);

        #-- find empty line and split cfg:block from help text part
        if (preg_match("/^(.+?)\n[ \t]*\n(.+)$/s", $src, $uu)) {
            $src = $uu[1];

            // add second part as help text
            $info["help"] = trim($uu[2]);
        }

        #-- read lines and name:value pairs
        preg_match_all("/^(\w+(?: \w+)?):\s*([^\n]*(\n[ ]+[^\n]+)*)/m", $src, $uu);
        /*                    |                    |
        this (?: \w+) is just for compatibility with wordpress plugin comments
        |
        lines with leading spaces (\n[ ]+ hold continuing description values
        */

        #-- add each line after trimming outer whitespace
        foreach ($uu[1] as $i => $tmp) {
            $info[strtolower($uu[1][$i])] = trim($uu[2][$i]);
        }

        if (isset($info["title"]) && isset($info["id"])) {
            return ($info);
        }
    }

    /**
     * gets first block of asterisk /* comment or # hash or // slash
     * comment, removes leading whitespace and comment characters
     *
     */
    private function extract_first_comment($src)
    {

        #-- clean out first line
        $src = preg_replace("/^<\?(php)?[^\n]*/i", "", $src);

        #-- extract /* ... */ comment block
        #  or lines of #... #... and //... //...
        if (preg_match("_^\s*/\*+(.+?)\*+/_s", $src, $uu)
            or (preg_match("_^\s*((^\s*(#+|//+)\s*.+?$\n)+)_ms", $src, $uu))) {
            $src = $uu[1];
        } else {
            return;
        }

        #-- cut comment/whitespace prefixes like _*__ or  __#_ or _//__ from
        #   lines - with same length from everyone! - don't care about actual
        #   pattern, but allow shortened lines (missing spaces after # or *)
        preg_match("_^([*#/ ]+)\w+( \w+)?:_m", $src, $uu);
        $n = @strlen($uu[1]);
        $src = preg_replace("_^[*#/ ]{0,$n}_m", "", $src);

        return ($src);
    }

    public function error($s)
    {
        $this->errors[] = $s;
        $this->error = 1;
    }

    public function has_errors()
    {
        if ($this->error == '1') {
            return true;
        }
        return false;
    }

    public function get_errors()
    {
        //return the errors array
        return $this->errors;
    }

    private function parse_options($str, $plugin)
    {
        $r = array();

        #-- search for < angle brackets > first
        preg_match_all("_<(\w+)(.+?)/\s*>_ims", $str, $uu);
        foreach ($uu[1] as $i => $optiontype) {
            $inner = $uu[2][$i];

            #-- prepare new
            $entry = array(
                "is" => $optiontype,
                "plugin" => $plugin,
            );

            #-- extract individual fields
            preg_match_all("_\s+([-\w:]+)=[\"\']([^\"\']*?)[\"\']_msi", $inner, $vv);
            foreach ($vv[1] as $j => $field) {
                $entry[$field] = $vv[2][$j];
            }

            #-- clean name=
            $entry["name"] = preg_replace("/[\$\"\'\s]/", "", $entry["name"]);

            #-- split up multi= value (our value= field holds the default entry instead)
            if (strpos($entry["multi"], "|")) {
                $opt = array();
                foreach (explode("|", $entry["multi"]) as $o) {
                    if (strpos($o, "=")) {
                        $opt[strtok($o, "=")] = strtok("\n");
                    } else {
                        $opt[$o] = $o;
                    }
                }
                $entry["multi"] = $opt;
            }

            #-- rename, just in case (actually default= is not recommended, and value= should be used)
            if (isset($row["default"]) && empty($row["value"])) {
                $row["value"] = $row["default"];
            }

            #-- add to list
            $r[] = $entry;
        }
        return ($r);
    }

    private function by($field, $cmp = NULL)
    {
        $r = array();
        foreach ($this->plugin as $id => $row) {
            if ((empty($cmp) && isset($row[$field]))
                or ($cmp == strtolower($row[$field]))) {
                $r[$row[$field]][$id] = $row;
            }
        }
        return ($r);
    }
}