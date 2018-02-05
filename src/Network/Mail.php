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
 * @since         1.0.0
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */
namespace Pabana\Network;

use \Pabana\Core\Configuration;
use \Pabana\Intl\Encoding;

class Mail
{
    private $armRecipient = array(
        'to' => array(),
        'cc' => array(),
        'bcc' => array()
    );
    private $armAttachment = array();
    private $armSender = array();
    private $armReply = array();
    private $sSubject = 'No title';
    private $sHtmlContent = '';
    private $sTextContent = '';
    private $sMailer = 'Pabana';
    private $nPriority = 1;
    private $sBoundary;
    private $sCharset;
    
    public function __construct()
    {
        $this->setBoundary();
        $this->sCharset = strtolower(Configuration::read('application.encoding'));
    }
    
    private function addAttachment($sAttachmentPath)
    {
        $this->armAttachment[] = array($sAttachmentPath);
    }
    
    private function addRecipient($sRecipientType, $sRecipientAddress, $sRecipientName = '')
    {
        if (filter_var($sRecipientAddress, FILTER_VALIDATE_EMAIL)) {
            $this->armRecipient[$sRecipientType][] = array($sRecipientAddress, $sRecipientName);
        }
    }
    
    public function addRecipientTo($sRecipientAddress, $sRecipientName = '')
    {
        $this->addRecipient('to', $sRecipientAddress, $sRecipientName);
    }
    
    public function addRecipientCc($sRecipientAddress, $sRecipientName = '')
    {
        $this->addRecipient('cc', $sRecipientAddress, $sRecipientName);
    }
    
    public function addRecipientBcc($sRecipientAddress, $sRecipientName = '')
    {
        $this->addRecipient('bcc', $sRecipientAddress, $sRecipientName);
    }
    
    public function setCharset($sCharset)
    {
        $this->sCharset = $sCharset;
    }
    
    public function setSender($sSenderAddress, $sSenderName = '')
    {
        $this->armSender = array($sSenderAddress, $sSenderName);
    }
    
    public function setReply($sReplyAddress, $sReplyName = '')
    {
        $this->armReply = array($sReplyAddress, $sReplyName);
    }
    
    public function setSubject($sSubject)
    {
        $this->sSubject = $sSubject;
    }
    
    public function setHtmlContent($sHtmlContent)
    {
        $this->sHtmlContent = $sHtmlContent;
    }
    
    public function setTextContent($sTextContent)
    {
        $this->sTextContent = $sTextContent;
    }
    
    public function setMailer($sMailer)
    {
        $this->sMailer = $sMailer;
    }
    
    public function setPriority($nPriority)
    {
        $this->nPriority = $nPriority;
    }
    
    private function setBoundary()
    {
        $this->sBoundary = uniqid('Pabana-Mail-') . '-' . md5(rand());
        $this->sBoundaryAlt = uniqid('Pabana-Mail-') . '-' . md5(rand());
    }
    
    public function getSender()
    {
        if (!empty($this->armSender)) {
            $sSender = '';
            if (!empty($this->armSender[1])) {
                $sSender .= '"' . $this->armSender[1] . '" ';
            }
            $sSender .= '<' . $this->armSender[0] . '>';
            return $sSender;
        } else {
            return false;
        }
    }
    
    public function getReply()
    {
        if (!empty($this->armReply)) {
            $sReply = '';
            if (!empty($this->armReply[1])) {
                $sReply .= '"' . $this->armReply[1] . '" ';
            }
            $sReply .= '<' . $this->armReply[0] . '>';
            return $sReply;
        } else {
            return false;
        }
    }
    
    public function getRecipientTo()
    {
        return $this->getRecipient('to');
    }
    
    public function getRecipientCc()
    {
        return $this->getRecipient('cc');
    }
    
    public function getRecipientBcc()
    {
        return $this->getRecipient('bcc');
    }
    
    private function getRecipient($sRecipientArray)
    {
        $armRecipientList = array();
        if (!empty($this->armRecipient[$sRecipientArray])) {
            $armRecipientList = '';
            foreach ($this->armRecipient[$sRecipientArray] as $armRecipient) {
                $sRecipient = '';
                if (!empty($armRecipient[1])) {
                    $sRecipient .= '"' . $armRecipient[1] . '" ';
                }
                $sRecipient .= '<' . $armRecipient[0] . '>';
                $armRecipientList[] = $sRecipient;
            }
        }
        return implode(', ', $armRecipientList);
    }
    
    public function getHeaderContent()
    {
        /* Header content */
        $sHeaderContent = '';
        $sSender = $this->getSender();
        if (!empty($sSender)) {
            $sHeaderContent .= 'From: ' . $sSender . PHP_EOL;
        }
        $sReply = $this->getReply();
        if (!empty($sReply)) {
            $sHeaderContent .= 'Reply-to: ' . $sReply . PHP_EOL;
        }
        $sRecipientCc = $this->getRecipientCc();
        if (!empty($sRecipientCc)) {
            $sHeaderContent .= 'Cc: ' . $sRecipientCc . PHP_EOL;
        }
        $sRecipientBcc = $this->getRecipientBcc();
        if (!empty($sRecipientBcc)) {
            $sHeaderContent .= 'Bcc: ' . $sRecipientBcc . PHP_EOL;
        }
        if (!empty($this->sMailer)) {
            $sHeaderContent .= 'X-Mailer: ' . $this->_sMailer . PHP_EOL;
        }
        $sHeaderContent .= 'MIME-Version: 1.0' . PHP_EOL;
        if (!empty($this->armAttachment)) {
            $sContentType = 'multipart/mixed';
        } else {
            $sContentType = 'multipart/alternative';
        }
        $sHeaderContent .= 'Content-Type: ' . $sContentType . '; boundary="' . $this->sBoundary . '"' . PHP_EOL . PHP_EOL;
        return $sHeaderContent;
    }
    
    public function getEmailContent()
    {
        $sMailContent = '';
        // Text content
        if (!empty($this->sTextContent)) {
            $sMailContent .= '--' . $this->sBoundary . PHP_EOL;
            $sMailContent .= 'Content-Type: text/plain; charset="' . $this->sCharset . '"' . PHP_EOL;
            $sMailContent .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
            $sMailContent .= $this->sTextContent . PHP_EOL . PHP_EOL;
        }
        // Html content
        if (!empty($this->sHtmlContent)) {
            $sMailContent .= '--' . $this->sBoundary . PHP_EOL;
            $sMailContent .= 'Content-Type: text/html; charset="' . $this->sCharset . '"' . PHP_EOL;
            $sMailContent .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
            $sMailContent .= $this->sHtmlContent . PHP_EOL . PHP_EOL;
        }
        $sMailContent .= '--' . $this->sBoundary . '--';
        return $sMailContent;
    }
    
    public function send()
    {
        $sAppEncoding = Configuration::read('application.encoding');
        $oEncoding = new Encoding();
        $sHeaderContent = $this->getHeaderContent();
        if ($sAppEncoding != $this->sCharset) {
            $sHeaderContent = $oEncoding->convert($sHeaderContent, $sAppEncoding, $this->sCharset);
        }
        $sMailContent = $this->getEmailContent();
        if ($sAppEncoding != $this->sCharset) {
            $sMailContent = $oEncoding->convert($sMailContent, $sAppEncoding, $this->sCharset);
        }
        return mail($this->getRecipientTo(), $this->sSubject, $sMailContent, $sHeaderContent);
    }
}
