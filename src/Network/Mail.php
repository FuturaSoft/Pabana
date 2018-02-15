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

/**
 * Mail class
 *
 * Send Mail
 */
class Mail
{
    /**
     * @var    Array List of recipent of mail
     * @since   1.0.0
     */
    private $armRecipient = array(
        'to' => array(),
        'cc' => array(),
        'bcc' => array()
    );

    /**
     * @var    Array List of attachement
     * @since   1.0.0
     */
    private $armAttachment = array();

    /**
     * @var    Array Sender of mail
     * @since   1.0.0
     */
    private $armSender = array();

    /**
     * @var    Array Reply adresse of mail
     * @since   1.0.0
     */
    private $armReply = array();

    /**
     * @var     string Subject of mail (by default "No title")
     * @since   1.0.0
     */
    private $sSubject = 'No title';

    /**
     * @var     string HTML content of mail
     * @since   1.0.0
     */
    private $sHtmlContent = '';

    /**
     * @var     string Texte content of mail
     * @since   1.0.0
     */
    private $sTextContent = '';

    /**
     * @var     string Mailer (by default "Pabana")
     * @since   1.0.0
     */
    private $sMailer = 'Pabana';

    /**
     * @var     integer Priority of mail (1 to 3)
     * @since   1.0.0
     */
    private $nPriority = 1;

    /**
     * @var     string Boundary of mail
     * @since   1.0.0
     */
    private $sBoundary;

    /**
     * @var     string Charset of mail
     * @since   1.0.0
     */
    private $sCharset;
    
    /**
     * Constructor
     *
     * Set Boundary and charset (by default application encoding)
     *
     * @since   1.0.0
     * @param   string $sCnxName Connection name.
     */
    public function __construct()
    {
        $this->setBoundary();
        $this->sCharset = strtolower(Configuration::read('application.encoding'));
    }
    
    /**
     * Add an attachment to mail
     *
     * @since   1.0.0
     * @param   string $sAttachmentPath Attachement path.
     * @return  void
     */
    public function addAttachment($sAttachmentPath)
    {
        $this->armAttachment[] = array($sAttachmentPath);
    }
    
    /**
     * Add a recipient of mail
     *
     * @since   1.0.0
     * @param   string $sRecipientType Type of recipient (to, cc, bcc).
     * @param   string $sRecipientAddress Email address of recipient.
     * @param   string $sRecipientName Name of recipient (optional).
     * @return  void
     */
    private function addRecipient($sRecipientType, $sRecipientAddress, $sRecipientName = '')
    {
        if (filter_var($sRecipientAddress, FILTER_VALIDATE_EMAIL)) {
            $this->armRecipient[$sRecipientType][] = array($sRecipientAddress, $sRecipientName);
        }
    }
    
    /**
     * Add a recipient "to" of mail
     *
     * @since   1.0.0
     * @param   string $sRecipientAddress Email address of recipient.
     * @param   string $sRecipientName Name of recipient (optional).
     * @return  void
     */
    public function addRecipientTo($sRecipientAddress, $sRecipientName = '')
    {
        $this->addRecipient('to', $sRecipientAddress, $sRecipientName);
    }
    
    /**
     * Add a recipient "cc" of mail
     *
     * @since   1.0.0
     * @param   string $sRecipientAddress Email address of recipient.
     * @param   string $sRecipientName Name of recipient (optional).
     * @return  void
     */
    public function addRecipientCc($sRecipientAddress, $sRecipientName = '')
    {
        $this->addRecipient('cc', $sRecipientAddress, $sRecipientName);
    }
    
    /**
     * Add a recipient "bcc" of mail
     *
     * @since   1.0.0
     * @param   string $sRecipientAddress Email address of recipient.
     * @param   string $sRecipientName Name of recipient (optional).
     * @return  void
     */
    public function addRecipientBcc($sRecipientAddress, $sRecipientName = '')
    {
        $this->addRecipient('bcc', $sRecipientAddress, $sRecipientName);
    }
    
    /**
     * Set charset use in mail
     *
     * @since   1.0.0
     * @param   string $sCharset Charset use.
     * @return  void
     */
    public function setCharset($sCharset)
    {
        $this->sCharset = $sCharset;
    }
    
    /**
     * Set sender of mail
     *
     * @since   1.0.0
     * @param   string $sSenderAddress Email address of sender.
     * @param   string $sSenderName Name of sender (optional).
     * @return  void
     */
    public function setSender($sSenderAddress, $sSenderName = '')
    {
        $this->armSender = array($sSenderAddress, $sSenderName);
    }
    
    /**
     * Set reply of mail
     *
     * @since   1.0.0
     * @param   string $sReplyAddress Email address of reply.
     * @param   string $sReplyName Name of reply (optional).
     * @return  void
     */
    public function setReply($sReplyAddress, $sReplyName = '')
    {
        $this->armReply = array($sReplyAddress, $sReplyName);
    }
    
    /**
     * Set subject of mail
     *
     * @since   1.0.0
     * @param   string $sSubject Subject of mail.
     * @return  void
     */
    public function setSubject($sSubject)
    {
        $this->sSubject = $sSubject;
    }
    
    /**
     * Set HTML content of mail
     *
     * @since   1.0.0
     * @param   string $sHtmlContent HTML content of mail.
     * @return  void
     */
    public function setHtmlContent($sHtmlContent)
    {
        $this->sHtmlContent = $sHtmlContent;
    }
    
    /**
     * Set text content of mail
     *
     * @since   1.0.0
     * @param   string $sTextContent Text content of mail.
     * @return  void
     */
    public function setTextContent($sTextContent)
    {
        $this->sTextContent = $sTextContent;
    }
    
    /**
     * Set mailer of mail
     *
     * @since   1.0.0
     * @param   string $sMailer Mailer of mail.
     * @return  void
     */
    public function setMailer($sMailer)
    {
        $this->sMailer = $sMailer;
    }
    
    /**
     * Set priority of mail
     *
     * @since   1.0.0
     * @param   integer $nPriority Priority of mail.
     * @return  void
     */
    public function setPriority($nPriority)
    {
        $this->nPriority = $nPriority;
    }
    
    /**
     * Generate boundary of mail
     *
     * @since   1.0.0
     * @return  void
     */
    private function setBoundary()
    {
        $this->sBoundary = uniqid('Pabana-Mail-') . '-' . md5(rand());
        $this->sBoundaryAlt = uniqid('Pabana-Mail-') . '-' . md5(rand());
    }
    
    /**
     * Get sender of mail
     *
     * @since   1.0.0
     * @return  string|bool Return sender of mail or false if not defined
     */
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
    
    /**
     * Get reply of mail
     *
     * @since   1.0.0
     * @return  string|bool Return reply of mail or false if not defined
     */
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
    
    /**
     * Get recipident "to" of mail
     *
     * @since   1.0.0
     * @return  string Return recipident "to" of mail
     */
    public function getRecipientTo()
    {
        return $this->getRecipient('to');
    }
    
    /**
     * Get recipident "cc" of mail
     *
     * @since   1.0.0
     * @return  string Return recipident "cc" of mail
     */
    public function getRecipientCc()
    {
        return $this->getRecipient('cc');
    }
    
    /**
     * Get recipident "bcc" of mail
     *
     * @since   1.0.0
     * @return  string Return recipident "bcc" of mail
     */
    public function getRecipientBcc()
    {
        return $this->getRecipient('bcc');
    }
    
    /**
     * Get recipident of mail
     *
     * @since   1.0.0
     * @param   string $sRecipientArray Type of recipient (to, cc, bcc)
     * @return  string Return recipident of mail
     */
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
    
    /**
     * Generate header content
     *
     * @since   1.0.0
     * @return  string Return header content of mail
     */
    public function getHeaderContent()
    {
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
            $sHeaderContent .= 'X-Mailer: ' . $this->sMailer . PHP_EOL;
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
    
    /**
     * Generate email content
     *
     * @since   1.0.0
     * @return  string Return email content
     */
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
    
    /**
     * Send email
     *
     * @since   1.0.0
     * @return  bool Return true if email is send with success else return false
     */
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