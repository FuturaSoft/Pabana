<?php
/**
 * Pabana : PHP Framework (https://pabana.futurasoft.fr)
 * Copyright (c) FuturaSoft (https://futurasoft.fr)
 *
 * Licensed under BSD-3-Clause License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) FuturaSoft (https://futurasoft.fr)
 * @link          https://pabana.futurasoft.fr Pabana Project
 * @since         1.2
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */
namespace Pabana\Form;

use Pabana\Network\Http\Request;

/**
 * Validate Form
 */
class Validate
{
    public static function cleanPhone($sValue)
    {
        if (substr($sValue, 0, 4) === '0033') {
            $sValue = '0' . substr($sValue, 4);
        }
        if (substr($sValue, 0, 3) === '+33') {
            $sValue = '0' . substr($sValue, 3);
        }
        if (substr($sValue, 0, 5) === '(+33)') {
            $sValue = '0' . substr($sValue, 5);
        }
        return str_replace(array(' ', '.', '-'), '', $sValue);
    }

    private static function checkLuhn($value)
    {
        $value = preg_replace('/[^\d]/', '', $value);
        $sum = '';
        for ($i = mb_strlen($value) - 1; $i >= 0; -- $i) {
            $sum .= $i & 1 ? $value[$i] : $value[$i] * 2;
        }
        return array_sum(str_split($sum)) % 10 === 0;
    }

    public static function isBase64($value)
    {
        if (base64_encode(base64_decode($value, true)) === $value) {
            return true;
        }
        return false;
    }

    public static function isBetween($value, $min, $max, $strict = false)
    {
        if (is_numeric($value)) {
            if ($strict === false) {
                if ($value >= $min && $value <= $max) {
                    return true;
                }
            } else {
                if ($value > $min && $value < $max) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function isBic($value)
    {
        return preg_match('/^[a-z]{6}[0-9a-z]{2}([0-9a-z]{3})?\z/i', $value);
    }

    public static function isBoolean($value)
    {
        $aAllowValue = [0, 1];
        return in_array($value, $aAllowValue);
    }

    public static function isChar($value)
    {
        return self::isStringLength($value, 1, 1);
    }

    public static function isColor($value, $type = false)
    {
        if ($type === false) {
            $typeCheck = array('hex', 'hsl', 'hsla', 'keyword', 'rgb', 'rgba');
        } else if (is_string($type)) {
            $typeCheck = array($type);
        } else {
            $typeCheck = $type;
        }
        if (in_array('hex', $typeCheck)) {
            if (preg_match('/#([a-f0-9]{3}){1,2}\b/i', $value)) {
                return true;
            }
        }
        if (in_array('hsl', $typeCheck)) {
            if (preg_match('hsl\(\s*\d+\s*(\s*\,\s*\d+\%){2}\)', $value)) {
                return true;
            }
        }
        if (in_array('hsla', $typeCheck)) {
            if (preg_match('hsla\(\s*\d+(\s*,\s*\d+\s*\%){2}\s*\,\s*[\d\.]+\)', $value)) {
                return true;
            }
        }
        if (in_array('rgb', $typeCheck)) {
            if (preg_match('rgb\((?:\s*\d+\s*,){2}\s*[\d]+\)', $value)) {
                return true;
            }
        }
        if (in_array('rgba', $typeCheck)) {
            if (preg_match('rgba\((\s*\d+\s*,){3}[\d\.]+\)', $value)) {
                return true;
            }
        }
        return false;
    }

    public static function isCreditCard($value, $type)
    {
        $arsRegexType = array(
            "visa" => "(4\d{12}(?:\d{3})?)",
            "amex" => "(3[47]\d{13})",
            "jcb" => "(35[2-8][89]\d\d\d{10})",
            "maestro" => "((?:5020|5038|6304|6579|6761)\d{12}(?:\d\d)?)",
            "solo" => "((?:6334|6767)\d{12}(?:\d\d)?\d?)",
            "mastercard" => "(5[1-5]\d{14})",
            "switch" => "(?:(?:(?:4903|4905|4911|4936|6333|6759)\d{12})|(?:(?:564182|633110)\d{10})(\d\d)?\d?)"
        );
        if (!preg_match($arsRegexType[$type], $value)) {
            return false;
        }
        return self::checkLuhn($value);
    }

    public static function isCcv($value)
    {
        return preg_match('/^[0-9]{4}$/', $value);
    }

    public static function isDate($value, $format, $min = false, $max = false)
    {
        $date = \DateTime::createFromFormat($format, $value);
        if ($date === false) {
            return false;
        }
        if ($min !== false) {
            $dateMin = \DateTime::createFromFormat($format, $min);
            if ($date < $dateMin) {
                return false;
            }
        }
        if ($max !== false) {
            $dateMax = \DateTime::createFromFormat($format, $max);
            if ($date > $dateMax) {
                return false;
            }
        }
        return true;
    }

    public static function isDifferent($value1, $value2)
    {
        if ($value1 == $value2) {
            return false;
        }
        return true;
    }

    public static function isEmailAddress($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public static function isEqual($value, $valueTest)
    {
        if ($value == $valueTest) {
            return true;
        }
        return false;
    }

    public static function isFile($value, $extension, $typeMime, $maxsize)
    {
        // Vérifie si erreur
        if ($value['error'] != 0) {
            return false;
        }
        // Check extension
        if ($extension != '*') {
            if (is_string($extension)) {
                $extension = array($extension);
            }
            $extension = array_map('strtolower', $extension);
            $fileExtension = pathinfo($value['name'], PATHINFO_EXTENSION);
            $fileExtension = strtolower($fileExtension);
            if (!in_array($fileExtension, $extension)) {
                return false;
            }
        }
        // Check type mime
        if ($typeMime != '*/*') {
            if (is_string($typeMime)) {
                $typeMime = array($typeMime);
            }
            if (!in_array($value['type'], $typeMime)) {
                return false;
            }
        }
        // Check size
        if ($value['size'] > $maxsize) {
            return false;
        }
        return true;
    }

    public static function isGreaterThan($value, $min)
    {
        if ($value < $min) {
            return false;
        }
        return true;
    }

    public static function isIban($value)
    {
        $iban = strtolower(str_replace(' ', '', $value));
        $countryCodeList = array(
            'al' => 28,
            'ad' => 24,
            'at' => 20,
            'az' => 28,
            'bh' => 22,
            'be' => 16,
            'ba' => 20,
            'br' => 29,
            'bg' => 22,
            'cr' => 21,
            'hr' => 21,
            'cy' => 28,
            'cz' => 24,
            'dk' => 18,
            'do' => 28,
            'ee' => 20,
            'fo' => 18,
            'fi' => 18,
            'fr' => 27,
            'ge' => 22,
            'de' => 22,
            'gi' => 23,
            'gr' => 27,
            'gl' => 18,
            'gt' => 28,
            'hu' => 28,
            'is' => 26,
            'ie' => 22,
            'il' => 23,
            'it' => 27,
            'jo' => 30,
            'kz' => 20,
            'kw' => 30,
            'lv' => 21,
            'lb' => 28,
            'li' => 21,
            'lt' => 20,
            'lu' => 20,
            'mk' => 19,
            'mt' => 31,
            'mr' => 27,
            'mu' => 30,
            'mc' => 27,
            'md' => 24,
            'me' => 22,
            'nl' => 18,
            'no' => 15,
            'pk' => 24,
            'ps' => 29,
            'pl' => 28,
            'pt' => 25,
            'qa' => 29,
            'ro' => 24,
            'sm' => 27,
            'sa' => 24,
            'rs' => 22,
            'sk' => 24,
            'si' => 19,
            'es' => 24,
            'se' => 24,
            'ch' => 21,
            'tn' => 24,
            'tr' => 26,
            'ae' => 23,
            'gb' => 22,
            'vg' => 24
        );
        $charCode = array(
            'a' => 10,
            'b' => 11,
            'c' => 12,
            'd' => 13,
            'e' => 14,
            'f' => 15,
            'g' => 16,
            'h' => 17,
            'i' => 18,
            'j' => 19,
            'k' => 20,
            'l' => 21,
            'm' => 22,
            'n' => 23,
            'o' => 24,
            'p' => 25,
            'q' => 26,
            'r' => 27,
            's' => 28,
            't' => 29,
            'u' => 30,
            'v' => 31,
            'w' => 32,
            'x' => 33,
            'y' => 34,
            'z' => 35
        );
        $ibanLength = mb_strlen($iban);
        $countryCode = substr($iban, 0, 2);
        if ($ibanLength == $countryCodeList[$countryCode]) {
            $movedChar = substr($iban, 4) . substr($iban, 0, 4);
            $movedCharArray = str_split($movedChar);
            $newString = "";
            foreach ($movedCharArray as $key => $value) {
                if (!is_numeric($movedCharArray[$key])) {
                    $movedCharArray[$key] = $charCode[$movedCharArray[$key]];
                }
                $newString .= $movedCharArray[$key];
            }
            if (bcmod($newString, '97') == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function isIdentical($value1, $value2)
    {
        if ($value1 != $value2) {
            return false;
        }
        return true;
    }

    public static function isIne($value)
    {
        if (!preg_match('#^((\d{9}[a-zA-Z]{2})|(\d{10}[a-zA-Z]{1}))$#', $value)) {
            return false;
        }
        return true;
    }

    public static function isInteger($value)
    {
        if (is_int($value)) {
            return true;
        }
        return false;
    }

    public static function isInArray($needle, $haystack)
    {
        return \in_array($needle, $haystack, true);
    }

    public static function isIp($value, $type = false)
    {
        $filterFlag = null;
        if ($type !== false) {
            if ($type == 'ipv4') {
                $filterFlag = FILTER_FLAG_IPV4;
            } else if ($type == 'ipv6') {
                $filterFlag = FILTER_FLAG_IPV6;
            }
        }
        if (filter_var($value, FILTER_VALIDATE_IP, $filterFlag)) {
            return true;
        }
        return false;
    }

    public static function isLessThan($value, $max)
    {
        if ($value > $max) {
            return false;
        }
        return true;
    }

    public static function isMac($value)
    {
        return filter_var($value, FILTER_VALIDATE_MAC);
    }

    public static function isNotEmpty($value)
    {
        if ($value == '') {
            return false;
        }
        return true;
    }

    public static function isNumeric($value)
    {
        if (is_numeric($value)) {
            return true;
        }
        return false;
    }

    public static function isPasswordStrength($value, $digit = false, $lower = false, $upper = false, $symbol = false)
    {
        if ($digit !== false) {
            if (!preg_match("#[0-9]+#", $value)) {
                return false;
            }
        }
        if ($lower !== false) {
            if (!preg_match("#[a-z]+#", $value)) {
                return false;
            }
        }
        if ($upper !== false) {
            if (!preg_match("#[A-Z]+#", $value)) {
                return false;
            }
        }
        if ($symbol !== false) {
            if (!preg_match("#\W+#", $value)) {
                return false;
            }
        }
        return true;
    }

    public static function isPhone($value, $country)
    {
        if ($country == 'FR') {
            return preg_match('#^(0|\\+33|0033|\(\\+33\))([-. ]?)[1-9]([-. ]?\d{2}){4}$#', $value);
        }
        return false;
    }

    public static function isRegexp($value, $regexp)
    {
        return preg_match($regexp, $value);
    }

    public static function isSiren($value)
    {
        if (mb_strlen($value) != 9) {
            return false;
        }
        if (!is_numeric($value)) {
            return false;
        }
        return self::checkLuhn($value);
    }

    public static function isSiret($value)
    {
        if (mb_strlen($value) != 14) {
            return false;
        }
        if (!is_numeric($value)) {
            return false;
        }
        return self::checkLuhn($value);
    }

    public static function isStringLength($value, $min = false, $max = false)
    {
        $value = stripslashes($value);
        if ($min !== false) {
            if (mb_strlen($value) < $min) {
                return false;
            }
        }
        if ($max !== false) {
            if (mb_strlen($value) > $max) {
                return false;
            }
        }
        return true;
    }

    public static function isUri($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL);
    }

    public static function isUuid($value, $type = 'v4')
    {
        $uuidList = array(
            'v4' => '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i'
        );
        return preg_match($uuidList[$type], $value);
    }

    public static function isVat($value)
    {
        $vatNumber = strtoupper(str_replace(' ', '', $value));
        $countryCode = substr($vatNumber, 0, 2);
        if ($countryCode == 'FR') {
            if (mb_strlen($vatNumber) != 13) {
                return false;
            }
            $siren = substr($vatNumber, 4);
            if (!self::isSiren($siren)) {
                return false;
            }
            $vatCheckCode = substr($vatNumber, 2, 2);
            $checkCode = (12 + 3 * ($siren % 97)) % 97;
            if ($vatCheckCode != $checkCode) {
                return false;
            }
        }
        return true;
    }

    public static function isZipCode($value, $country)
    {
        $armZipRegex = array(
            "US" => "^\d{5}([\-]?\d{4})?$",
            "UK" => "^(GIR|[A-Z]\d[A-Z\d]??|[A-Z]{2}\d[A-Z\d]??)[ ]??(\d[A-Z]{2})$",
            "DE" => "\b((?:0[1-46-9]\d{3})|(?:[1-357-9]\d{4})|(?:[4][0-24-9]\d{3})|(?:[6][013-9]\d{3}))\b",
            "CA" => "^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])\ {0,1}(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$",
            "FR" => "^(F-)?((2[A|B])|[0-9]{2})[0-9]{3}^",
            "IT" => "^(V-|I-)?[0-9]{5}$",
            "AU" => "^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$",
            "NL" => "^[1-9][0-9]{3}\s?([a-zA-Z]{2})?$",
            "ES" => "^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$",
            "DK" => "^([D-d][K-k])?( |-)?[1-9]{1}[0-9]{3}$",
            "SE" => "^(s-|S-){0,1}[0-9]{3}\s?[0-9]{2}$",
            "BE" => "^[1-9]{1}[0-9]{3}$"
        );
        return preg_match($armZipRegex[$country], $value);
    }

    public static function prepare($aData, $aPrepareRule = [])
    {
        if (empty($aPrepareRule)) {

        }
        // Parcours les champs a preparer
        foreach ($aPrepareRule as $sField => $aFieldRule) {
            $bTrim = true;
            $bHtmlSpecialchars = true;
            $bAddslashes = true;
            $bStripTags = false;
            $bCleanPhone = false;
            if (isset($aFieldRule['prepare'])) {
                if (isset($aFieldRule['prepare']['trim'])) {
                    $bTrim = $aFieldRule['prepare']['trim'];
                }
                if (isset($aFieldRule['prepare']['htmlspecialchars'])) {
                    $bHtmlSpecialchars = $aFieldRule['prepare']['htmlspecialchars'];
                }
                if (isset($aFieldRule['prepare']['addslashes'])) {
                    $bAddslashes = $aFieldRule['prepare']['addslashes'];
                }
                if (isset($aFieldRule['prepare']['stripTags'])) {
                    $bStripTags = $aFieldRule['prepare']['stripTags'];
                }
                if (isset($aFieldRule['prepare']['cleanPhone'])) {
                    $bCleanPhone = $aFieldRule['prepare']['cleanPhone'];
                }
            }
            if (isset($aData[$sField])) {
                $sFieldContent = $aData[$sField];
                if ($bTrim) {
                    if (is_array($sFieldContent)) {
                        $sFieldContent = self::recursivePrepare('trim', $sFieldContent);
                    } else {
                        $sFieldContent = trim($sFieldContent);
                    }
                }
                if ($bStripTags) {
                    if (is_array($sFieldContent)) {
                        $sFieldContent = self::recursivePrepare('strip_tags', $sFieldContent);
                    } else {
                        $sFieldContent = strip_tags($sFieldContent);
                    }
                }
                if ($bHtmlSpecialchars) {
                    if (is_array($sFieldContent)) {
                        $sFieldContent = self::recursivePrepare('htmlspecialchars', $sFieldContent);
                    } else {
                        $sFieldContent = htmlspecialchars($sFieldContent);
                    }
                }
                if ($bAddslashes) {
                    if (is_array($sFieldContent)) {
                        $sFieldContent = self::recursivePrepare('addslashes', $sFieldContent);
                    } else {
                        $sFieldContent = addslashes($sFieldContent);
                    }
                }
                if ($bCleanPhone) {
                    if (is_array($sFieldContent)) {
                        $sFieldContent = self::recursivePrepare('clean_phone', $sFieldContent);
                    } else {
                        $sFieldContent = self::cleanPhone($sFieldContent);
                    }
                }
                $aData[$sField] = $sFieldContent;
            } else {
                $aData[$sField] = '';
            }
        }
        return $aData;
    }

    public static function recursivePrepare($sCallback, $aArray)
    {
        $aReturn = array();
        foreach ($aArray as $mKey => $mValue) {
            if (is_array($mValue)) {
                $mReturnValue = self::recursivePrepare($sCallback, $mValue);
            } else {
                if ($sCallback == 'trim') {
                    $mReturnValue = trim($mValue);
                } else if ($sCallback == 'strip_tags') {
                    $mReturnValue = strip_tags($mValue);
                } else if ($sCallback == 'htmlspecialchars') {
                    $mReturnValue = htmlspecialchars($mValue);
                } else if ($sCallback == 'addslashes') {
                    $mReturnValue = addslashes($mValue);
                } else if ($sCallback == 'clean_phone') {
                    $mReturnValue = self::cleanPhone($mValue);
                }
            }
            $aReturn[$mKey] = $mReturnValue;
        }
        return $aReturn;
    }

    public static function validate($aValidRule, $aData)
    {
        // Parcours les champs a valider
        foreach ($aValidRule as $sField => $aFieldRule) {
            // Get rule list for this field
            $aRuleTypeList = array_keys($aFieldRule);
            // Check if field isn't set
            if (!isset($aData[$sField])) {
                // If field isn't set and isn't required, skip other test
                if (!in_array('required', $aRuleTypeList)) {
                    continue;
                } else { // Return required error
                    $aReturnError = [
                        'name' => $sField,
                        'error' => 'required'
                    ];
                    if (isset($aFieldRule['required']['message'])) {
                        $aReturnError['message'] = $aFieldRule['required']['message'];
                    }
                    return $aReturnError;
                }
            }
            // Check if field is empty
            if ($aData[$sField] == '') {
                // If field is empty but isn't a requirement, skip other test
                if (!in_array('notEmpty', $aRuleTypeList)) {
                    continue;
                } else { // Return required error
                    $aReturnError = [
                        'name' => $sField,
                        'error' => 'notEmpty'
                    ];
                    if (isset($aFieldRule['notEmpty']['message'])) {
                        $aReturnError['message'] = $aFieldRule['notEmpty']['message'];
                    }
                    return $aReturnError;
                }
            }
            // Check all validation rules
            foreach ($aFieldRule as $sRuleType => $aRuleData) {
                $aReturn = [
                    'name' => $sField,
                    'error' => $sRuleType
                ];
                if (isset($aRuleData['message'])) {
                    $aReturn['message'] = $aRuleData['message'];
                }
                if ($sRuleType == 'base64') {
                    if (!self::isBase64($aRuleData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'between') {
                    $min = $aRuleData['min'];
                    $max = $aRuleData['max'];
                    if (!self::isBetween($aData[$sField], $min, $max)) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'bic') {
                    if (!self::isBic($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'boolean') {
                    if (!self::isBoolean($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'char') {
                    if (!self::isChar($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'color') {
                    $type = false;
                    if (isset($aRuleData['type'])) {
                        $type = $aRuleData['type'];
                    }
                    if (!self::isColor($aData[$sField], $type)) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'creditcard') {
                    $type = $aRuleData['type'];
                    if (!self::isCreditCard($aData[$sField], $type)) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'ccv') {
                    if (!self::isCcv($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'date') {
                    $format = $aRuleData['format'];
                    $min = false;
                    if (isset($aRuleData['min'])) {
                        $min = $aRuleData['min'];
                    }
                    $max = false;
                    if (isset($aRuleData['max'])) {
                        $max = $aRuleData['max'];
                    }
                    if (!self::isDate($aData[$sField], $format, $min, $max)) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'different') {
                    $sFieldTest = $aRuleData['field'];
                    if (!self::isDifferent($aData[$sField], $aData[$sFieldTest])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'emailAddress') {
                    if (!self::isEmailAddress($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'equal') {
                    $sValueTest = $aRuleData['value'];
                    if (!self::isEqual($aData[$sField], $sValueTest)) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'file') {
                    $extension = false;
                    if (isset($aRuleData['extension'])) {
                        $extension = $aRuleData['extension'];
                    }
                    $mimetype = false;
                    if (isset($aRuleData['mimetype'])) {
                        $mimetype = $aRuleData['mimetype'];
                    }
                    $maxsize = false;
                    if (isset($aRuleData['maxsize'])) {
                        $maxsize = $aRuleData['maxsize'];
                    }
                    if (!self::isFile($aData[$sField], $extension, $mimetype, $maxsize)) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'greaterThan') {
                    $min = $aRuleData['min'];
                    if (!self::isGreaterThan($aData[$sField], $min)) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'iban') {
                    if (!self::isIban($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'ip') {
                    $type = false;
                    if (isset($aRuleData['type'])) {
                        $type = $aRuleData['type'];
                    }
                    if (!self::isIban($aData[$sField], $type)) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'identical') {
                    $sFieldTest = $aRuleData['field'];
                    if (!self::isIdentical($aData[$sField], $aData[$sFieldTest])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'integer') {
                    if (!self::isInteger($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'lessThan') {
                    $max = $aRuleData['max'];
                    if (!self::isLessThan($aData[$sField], $max)) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'mac') {
                    if (!self::isMac($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'notEmpty') {
                    if (!self::isNotEmpty($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'numeric') {
                    if (!self::isNumeric($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'passwordStrength') {
                    $digit = false;
                    if (isset($aRuleData['digit'])) {
                        $digit = $aRuleData['digit'];
                    }
                    $lower = false;
                    if (isset($aRuleData['lower'])) {
                        $lower = $aRuleData['lower'];
                    }
                    $upper = false;
                    if (isset($aRuleData['upper'])) {
                        $upper = $aRuleData['upper'];
                    }
                    $symbol = false;
                    if (isset($aRuleData['symbol'])) {
                        $symbol = $aRuleData['symbol'];
                    }
                    if (!self::isPasswordStrength($aData[$sField], $digit, $lower, $upper, $symbol)) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'phone') {
                    $country = $aRuleData['country'];
                    if (!self::isPhone($aData[$sField], $country)) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'regexp') {
                    $regexp = $aRuleData['regexp'];
                    if (!self::isRegexp($aData[$sField], $regexp)) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'siren') {
                    if (!self::isSiren($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'siret') {
                    if (!self::isSiret($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'stringLength') {
                    $min = false;
                    if (isset($aRuleData['min'])) {
                        $min = $aRuleData['min'];
                    }
                    $max = false;
                    if (isset($aRuleData['max'])) {
                        $max = $aRuleData['max'];
                    }
                    if (!self::isStringLength($aData[$sField], $min, $max)) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'uri') {
                    if (!self::isUri($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'uuid') {
                    if (!self::isUuid($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'vat') {
                    if (!self::isVat($aData[$sField])) {
                        return $aReturn;
                    }
                } else if ($sRuleType == 'zipCode') {
                    $country = $aRuleData['country'];
                    if (!self::isZipCode($aData[$sField], $country)) {
                        return $aReturn;
                    }
                } else if ($sRuleType === 'inArray') {
                    if (!self::isInArray($aData[$sField], $aRuleData['haystack'])) {
                        return $aReturn;
                    }
                }
            }
        }
        return true;
    }
}
