<?php

namespace dee\angular;

/**
 * Description of Helper
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Helper
{

    /**
     * Only get script inner of `script` tag.
     * @param string $js
     * @return string
     */
    public static function parseBlockJs($js)
    {
        $jsBlockPattern = '|^<script[^>]*>(?P<block_content>.+?)</script>$|is';
        if (preg_match($jsBlockPattern, trim($js), $matches)) {
            $js = trim($matches['block_content']);
        }
        return $js;
    }

    /**
     *
     * @param string $input
     * @return string
     */
    public static function minifyHtml($input)
    {
        // https://gist.github.com/tovic/d7b310dea3b33e4732c0
        if (trim($input) === "") {
            return $input;
        }
        // Remove extra white-spaces between HTML attributes
        $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
            return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
        }, $input);

        $replaces = [
            // Remove HTML comments except IE comments
            '#\s*(<\!--(?=\[if).*?-->)\s*|\s*<\!--.*?-->\s*#s' => '$1',
            // Do not remove white-space after image and
            // input tag that is followed by a tag open
            '#(<(?:img|input)(?:\/?>|\s[^<>]*?\/?>))\s+(?=\<[^\/])#s' => '$1&nbsp;',
            // Remove two or more white-spaces between tags
            '#(<\!--.*?-->)|(>)\s{2,}|\s{2,}(<)|(>)\s{2,}(<)#s' => '$1$2$3$4$5',
            // Proofing ...
            // o: tag open, c: tag close, t: text
            // If `<tag> </tag>` remove white-space
            // If `</tag> <tag>` keep white-space
            // If `<tag> <tag>` remove white-space
            // If `</tag> </tag>` remove white-space
            // If `<tag>    ...</tag>` remove white-spaces
            // If `</tag>    ...<tag>` remove white-spaces
            // If `<tag>    ...<tag>` remove white-spaces
            // If `</tag>    ...</tag>` remove white-spaces
            // If `abc <tag>` keep white-space
            // If `<tag> abc` remove white-space
            // If `abc </tag>` remove white-space
            // If `</tag> abc` keep white-space
            // TODO: If `abc    ...<tag>` keep one white-space
            // If `<tag>    ...abc` remove white-spaces
            // If `abc    ...</tag>` remove white-spaces
            // TODO: If `</tag>    ...abc` keep one white-space
            '#(<\!--.*?-->)|(<(?:img|input)(?:\/?>|\s[^<>]*?\/?>))\s+(?!\<\/)#s' => '$1$2&nbsp;', // o+t | o+o
            '#(<\!--.*?-->)|(<[^\/\s<>]+(?:>|\s[^<>]*?>))\s+(?=\<[^\/])#s' => '$1$2', // o+o
            '#(<\!--.*?-->)|(<\/[^\/\s<>]+?>)\s+(?=\<\/)#s' => '$1$2', // c+c
            '#(<\!--.*?-->)|(<([^\/\s<>]+)(?:>|\s[^<>]*?>))\s+(<\/\3>)#s' => '$1$2$4', // o+c
            '#(<\!--.*?-->)|(<[^\/\s<>]+(?:>|\s[^<>]*?>))\s+(?!\<)#s' => '$1$2', // o+t
            '#(<\!--.*?-->)|(?!\>)\s+(<\/[^\/\s<>]+?>)#s' => '$1$2', // t+c
            '#(<\!--.*?-->)|(?!\>)\s+(?=\<[^\/])#s' => '$1$2 ', // t+o
            '#(<\!--.*?-->)|(<\/[^\/\s<>]+?>)\s+(?!\<)#s' => '$1$2 ', // c+t
            '#(<\!--.*?-->)|(\/>)\s+(?!\<)#' => '$1$2 ', // o+t
            // Replace `&nbsp;&nbsp;&nbsp;` with `&nbsp; &nbsp;`
            '#(?<=&nbsp;)(&nbsp;){2}#' => ' $1',
            // Proofing ...
            '#(?<=\>)&nbsp;(?!\s|&nbsp;|<\/)#' => ' ',
            '#(?<=--\>)(?:\s|&nbsp;)+(?=\<)#' => "",
        ];
        return preg_replace(array_keys($replaces), array_values($replaces), trim($input));
    }
}
