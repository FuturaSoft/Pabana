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
 * @since         1.0
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
     * @since   1.0
     */
    private $recipientList = array(
        'to' => array(),
        'cc' => array(),
        'bcc' => array()
    );

    /**
     * @var    Array List of attachement
     * @since   1.0
     */
    private $attachmentList = array();

    /**
     * @var    Array Sender of mail
     * @since   1.0
     */
    private $sender = array();

    /**
     * @var    Array Reply adresse of mail
     * @since   1.0
     */
    private $reply = array();

    /**
     * @var     string Subject of mail (by default "No title")
     * @since   1.0
     */
    private $subject = 'No title';

    /**
     * @var     string HTML content of mail
     * @since   1.0
     */
    private $htmlContent = '';

    /**
     * @var     string Texte content of mail
     * @since   1.0
     */
    private $textContent = '';

    /**
     * @var     string Mailer (by default "Pabana")
     * @since   1.0
     */
    private $mailer = 'Pabana';

    /**
     * @var     integer Priority of mail (1 to 3)
     * @since   1.0
     */
    private $priority = 1;

    /**
     * @var     string Boundary of mail
     * @since   1.0
     */
    private $boundary;

    /**
     * @var     string Boundary Alt of mail
     * @since   1.0
     */
    private $boundaryAlt;

    /**
     * @var     string Charset of mail
     * @since   1.0
     */
    private $charset;
    
    /**
     * Constructor
     *
     * Set Boundary and charset (by default application encoding)
     *
     * @since   1.0
     * @param   string $sCnxName Connection name.
     */
    public function __construct()
    {
        $this->setBoundary();
        $this->charset = strtolower(Configuration::read('application.encoding'));
    }
    
    /**
     * Add an attachment to mail
     *
     * @since   1.0
     * @param   string $attachmentPath Attachement path.
     * @return  void
     */
    public function addAttachment($attachmentPath)
    {
        $this->attachmentList[] = array($attachmentPath);
    }

    /**
     * Add encode tag
     *
     * @since   1.0
     * @param   string $value Value to encapsulate in encode tag.
     * @return  void
     */
    private function addEncodeTag($value)
    {
        return '=?' . $this->charset . '?Q?' . $value . '?=';
    }
    
    /**
     * Add a recipient of mail
     *
     * @since   1.0
     * @param   string $recipientType Type of recipient (to, cc, bcc).
     * @param   string $recipientAddress Email address of recipient.
     * @param   string $recipientName Name of recipient (optional).
     * @return  void
     */
    private function addRecipient($recipientType, $recipientAddress, $recipientName = '')
    {
        if (filter_var($recipientAddress, FILTER_VALIDATE_EMAIL)) {
            $this->recipientList[$recipientType][] = array($recipientAddress, $sRecipientName);
        }
    }
    
    /**
     * Add a recipient "to" of mail
     *
     * @since   1.0
     * @param   string $recipientAddress Email address of recipient.
     * @param   string $recipientName Name of recipient (optional).
     * @return  void
     */
    public function addRecipientTo($recipientAddress, $recipientName = '')
    {
        $this->addRecipient('to', $recipientAddress, $recipientName);
    }
    
    /**
     * Add a recipient "cc" of mail
     *
     * @since   1.0
     * @param   string $recipientAddress Email address of recipient.
     * @param   string $recipientName Name of recipient (optional).
     * @return  void
     */
    public function addRecipientCc($recipientAddress, $recipientName = '')
    {
        $this->addRecipient('cc', $recipientAddress, $recipientName);
    }
    
    /**
     * Add a recipient "bcc" of mail
     *
     * @since   1.0
     * @param   string $recipientAddress Email address of recipient.
     * @param   string $recipientName Name of recipient (optional).
     * @return  void
     */
    public function addRecipientBcc($recipientAddress, $recipientName = '')
    {
        $this->addRecipient('bcc', $recipientAddress, $recipientName);
    }
    
    /**
     * Set charset use in mail
     *
     * @since   1.0
     * @param   string $charset Charset use.
     * @return  void
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }
    
    /**
     * Set sender of mail
     *
     * @since   1.0
     * @param   string $senderAddress Email address of sender.
     * @param   string $senderName Name of sender (optional).
     * @return  void
     */
    public function setSender($senderAddress, $senderName = '')
    {
        $this->sender = array($senderAddress, $senderName);
    }
    
    /**
     * Set reply of mail
     *
     * @since   1.0
     * @param   string $replyAddress Email address of reply.
     * @param   string $replyName Name of reply (optional).
     * @return  void
     */
    public function setReply($replyAddress, $replyName = '')
    {
        $this->reply = array($replyAddress, $replyName);
    }
    
    /**
     * Set subject of mail
     *
     * @since   1.0
     * @param   string $subject Subject of mail.
     * @return  void
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }
    
    /**
     * Set HTML content of mail
     *
     * @since   1.0
     * @param   string $htmlContent HTML content of mail.
     * @return  void
     */
    public function setHtmlContent($htmlContent)
    {
        $this->htmlContent = $htmlContent;
    }
    
    /**
     * Set text content of mail
     *
     * @since   1.0
     * @param   string $textContent Text content of mail.
     * @return  void
     */
    public function setTextContent($textContent)
    {
        $this->textContent = $textContent;
    }
    
    /**
     * Set mailer of mail
     *
     * @since   1.0
     * @param   string $mailer Mailer of mail.
     * @return  void
     */
    public function setMailer($mailer)
    {
        $this->mailer = $mailer;
    }
    
    /**
     * Set priority of mail
     *
     * @since   1.0
     * @param   integer $priority Priority of mail.
     * @return  void
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }
    
    /**
     * Generate boundary of mail
     *
     * @since   1.0
     * @return  void
     */
    private function setBoundary()
    {
        $this->boundary = uniqid('Pabana') . '-' . md5(rand());
        $this->boundaryAlt = uniqid('Pabana-alt') . '-' . md5(rand());
    }
    
    /**
     * Get sender of mail
     *
     * @since   1.0
     * @return  string|bool Return sender of mail or false if not defined
     */
    public function getSender()
    {
        if (!empty($this->sender)) {
            $sender = '';
            if (!empty($this->sender[1])) {
                $sender .= $this->addEncodeTag('"' . $this->sender[1] . '"') . ' ';
            }
            $sender .= '<' . $this->sender[0] . '>';
            return $sender;
        } else {
            return false;
        }
    }
    
    /**
     * Get reply of mail
     *
     * @since   1.0
     * @return  string|bool Return reply of mail or false if not defined
     */
    public function getReply()
    {
        if (!empty($this->reply)) {
            $reply = '';
            if (!empty($this->reply[1])) {
                $reply .= $this->addEncodeTag('"' . $this->reply[1] . '"') . ' ';
            }
            $reply .= '<' . $this->reply[0] . '>';
            return $reply;
        } else {
            return false;
        }
    }
    
    /**
     * Get recipident "to" of mail
     *
     * @since   1.0
     * @return  string Return recipident "to" of mail
     */
    public function getRecipientTo()
    {
        return $this->getRecipient('to');
    }
    
    /**
     * Get recipident "cc" of mail
     *
     * @since   1.0
     * @return  string Return recipident "cc" of mail
     */
    public function getRecipientCc()
    {
        return $this->getRecipient('cc');
    }
    
    /**
     * Get recipident "bcc" of mail
     *
     * @since   1.0
     * @return  string Return recipident "bcc" of mail
     */
    public function getRecipientBcc()
    {
        return $this->getRecipient('bcc');
    }
    
    /**
     * Get recipident of mail
     *
     * @since   1.0
     * @param   string $recipientType Type of recipient (to, cc, bcc)
     * @return  string Return recipident of mail
     */
    private function getRecipient($recipientType)
    {
        $returnList = array();
        if (!empty($this->recipientList[$recipientType])) {
            foreach ($this->recipientList[$recipientType] as $recipientItem) {
                $recipient = '';
                if (!empty($recipientItem[1])) {
                    $recipient .= $this->addEncodeTag('"' . $this->recipientItem[1] . '"') . ' ';
                }
                $recipient .= '<' . $recipientItem[0] . '>';
                $returnList[] = $recipient;
            }
        }
        return implode(', ', $returnList);
    }
    
    /**
     * Generate header content
     *
     * @since   1.0
     * @return  string Return header content of mail
     */
    public function getHeaderContent()
    {
        $headerContent = '';
        $sender = $this->getSender();
        if (!empty($sender)) {
            $headerContent .= 'From: ' . $sender . PHP_EOL;
        }
        $reply = $this->getReply();
        if (!empty($reply)) {
            $headerContent .= 'Reply-to: ' . $reply . PHP_EOL;
        }
        $recipientCc = $this->getRecipientCc();
        if (!empty($recipientCc)) {
            $headerContent .= 'Cc: ' . $recipientCc . PHP_EOL;
        }
        $recipientBcc = $this->getRecipientBcc();
        if (!empty($recipientBcc)) {
            $headerContent .= 'Bcc: ' . $recipientBcc . PHP_EOL;
        }
        if (!empty($this->mailer)) {
            $headerContent .= 'X-Mailer: ' . $this->mailer . PHP_EOL;
        }
        $headerContent .= 'MIME-Version: 1.0' . PHP_EOL;
        if (!empty($this->attachmentList)) {
            $contentType = 'multipart/mixed';
        } else {
            $contentType = 'multipart/alternative';
        }
        $headerContent .= 'Content-Type: ' . $contentType . '; boundary="' . $this->boundary . '"';
        $headerContent .= PHP_EOL . PHP_EOL;
        return $headerContent;
    }
    
    /**
     * Generate email content
     *
     * @since   1.0
     * @return  string Return email content
     */
    public function getEmailContent()
    {
        $mailContent = '';
        // Text content
        if (!empty($this->textContent)) {
            $mailContent .= '--' . $this->boundary . PHP_EOL;
            $mailContent .= 'Content-Type: text/plain; charset="' . $this->charset . '"' . PHP_EOL;
            $mailContent .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
            $mailContent .= $this->textContent . PHP_EOL . PHP_EOL;
        }
        // Html content
        if (!empty($this->htmlContent)) {
            $mailContent .= '--' . $this->boundary . PHP_EOL;
            $mailContent .= 'Content-Type: text/html; charset="' . $this->charset . '"' . PHP_EOL;
            $mailContent .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
            $mailContent .= $this->htmlContent . PHP_EOL . PHP_EOL;
        }
        $mailContent .= '--' . $this->boundary . '--';
        return $mailContent;
    }
    
    /**
     * Send email
     *
     * @since   1.0
     * @return  bool Return true if email is send with success else return false
     */
    public function send()
    {
        $appEncoding = Configuration::read('application.encoding');
        $encoding = new Encoding();
        $subject = $this->addEncodeTag($this->subject);
        $headerContent = $this->getHeaderContent();
        $mailContent = $this->getEmailContent();
        if ($appEncoding != $this->charset) {
            $subject = $encoding->convert($subject, $appEncoding, $this->charset);
            $headerContent = $encoding->convert($headerContent, $appEncoding, $this->charset);
            $mailContent = $encoding->convert($mailContent, $appEncoding, $this->charset);
        }
        return mail($this->getRecipientTo(), $this->subject, $mailContent, $headerContent);
    }
}
