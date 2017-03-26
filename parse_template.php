<?php
/**
 * @param string $template
 * @param bool $recursion shows if this is first call of function or from recursion
 * @return array
 * @throws Exception If quantity of open and close symbols in template are not equal
 */
function parseTemplate($template, $recursion = false) {

    static $result = [];

    if (!$recursion) {
        if (mb_substr_count($template, '[') != mb_substr_count($template, ']')) {
            throw new Exception ("Bad template given. Quantity of open and close symbols in template are not equal");
        }
    }

    if (preg_match("/^(.*)\[([^\[\]]+)\](.*)$/U", $template, $matches)) {

        $match_variants = explode('|', $matches[2]);

        foreach ($match_variants as $variant) {
            parseTemplate($matches[1] . $variant . $matches[3], true);
        }

    } else {
        $result[] = $template;
    }

    return array_values(array_unique($result));

}

$templates = [
    "Просто строка без шаблона",
    "[Написать|Закодить|Придумать] генератор [фраз|предложений|текстов] по [шаблону|правилу|образцу]",
    "[[Написать|Закодить|Придумать] генератор [фраз|предложений|текстов] по [шаблону|правилу|образцу]|Спеть красивую песню] чтобы выполнить задание.",
    "[[Написать|Закодить|Придумать [как можно быстрее|скорее]] генератор [[разных|всяких] фраз|предложений|текстов] по [шаблону|правилу|образцу]|[Спеть красивую песню|Станцевать]] чтобы выполнить задание."
];

try {

    foreach ($templates as $template) {
        print_r(parseTemplate($template));
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
