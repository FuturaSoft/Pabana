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
     * @var    array List of recipent of mail
     * @since   1.0
     */
    private array $recipientList = [
        'to' => [],
        'cc' => [],
        'bcc' => [],
    ];

    /**
     * @var    array List of attachement
     * @since   1.0
     */
    private array $attachmentList = [];

    /**
     * @var    array Sender of mail
     * @since   1.0
     */
    private array $sender = [];

    /**
     * @var    array Reply adresse of mail
     * @since   1.0
     */
    private array $reply = [];

    /**
     * @var     string Subject of mail (by default "No title")
     * @since   1.0
     */
    private string $subject = 'No title';

    /**
     * @var     string HTML content of mail
     * @since   1.0
     */
    private string $htmlContent = '';

    /**
     * @var     string Texte content of mail
     * @since   1.0
     */
    private string $textContent = '';

    /**
     * @var     string Mailer (by default "Pabana")
     * @since   1.0
     */
    private string $mailer = 'Pabana';

    /**
     * @var     int Priority of mail (1 to 3)
     * @since   1.0
     */
    private int $priority = 1;

    /**
     * @var     string Boundary of mail
     * @since   1.0
     */
    private string $boundary;

    /**
     * @var     string Boundary Alt of mail
     * @since   1.0
     */
    private string $boundaryAlt;

    /**
     * @var     string Charset of mail
     * @since   1.0
     */
    private string $charset;

    /**
     * Constructor
     *
     * Set Boundary and charset (by default application encoding)
     *
     * @since   1.0
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
    public function addAttachment(string $attachmentPath): void
    {
        $this->attachmentList[] = [$attachmentPath];
    }

    /**
     * Add encode tag
     *
     * @since   1.0
     * @param   string $value Value to encapsulate in encode tag.
     * @return  string
     */
    private function addEncodeTag(string $value): string
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
    private function addRecipient(string $recipientType, string $recipientAddress, string $recipientName = ''): void
    {
        if (filter_var($recipientAddress, FILTER_VALIDATE_EMAIL)) {
            $this->recipientList[$recipientType][] = [$recipientAddress, $recipientName];
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
    public function addRecipientTo(string $recipientAddress, string $recipientName = ''): void
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
    public function addRecipientCc(string $recipientAddress, string $recipientName = ''): void
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
    public function addRecipientBcc(string $recipientAddress, string $recipientName = ''): void
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
    public function setCharset(string $charset): void
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
    public function setSender(string $senderAddress, string $senderName = ''): void
    {
        $this->sender = [$senderAddress, $senderName];
    }

    /**
     * Set reply of mail
     *
     * @since   1.0
     * @param   string $replyAddress Email address of reply.
     * @param   string $replyName Name of reply (optional).
     * @return  void
     */
    public function setReply(string $replyAddress, string $replyName = ''): void
    {
        $this->reply = [$replyAddress, $replyName];
    }

    /**
     * Set subject of mail
     *
     * @since   1.0
     * @param   string $subject Subject of mail.
     * @return  void
     */
    public function setSubject(string $subject): void
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
    public function setHtmlContent(string $htmlContent): void
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
    public function setTextContent(string $textContent): void
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
    public function setMailer(string $mailer): void
    {
        $this->mailer = $mailer;
    }

    /**
     * Set priority of mail
     *
     * @since   1.0
     * @param   int $priority Priority of mail.
     * @return  void
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * Generate boundary of mail
     *
     * @since   1.0
     * @return  void
     */
    private function setBoundary(): void
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
    public function getSender(): string|false
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
    public function getReply(): string|false
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
    public function getRecipientTo(): string
    {
        return $this->getRecipient('to');
    }

    /**
     * Get recipident "cc" of mail
     *
     * @since   1.0
     * @return  string Return recipident "cc" of mail
     */
    public function getRecipientCc(): string
    {
        return $this->getRecipient('cc');
    }

    /**
     * Get recipident "bcc" of mail
     *
     * @since   1.0
     * @return  string Return recipident "bcc" of mail
     */
    public function getRecipientBcc(): string
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
    private function getRecipient(string $recipientType): string
    {
        $returnList = [];
        if (!empty($this->recipientList[$recipientType])) {
            foreach ($this->recipientList[$recipientType] as $recipientItem) {
                $recipient = '';
                if (!empty($recipientItem[1])) {
                    $recipient .= $this->addEncodeTag('"' . $recipientItem[1] . '"') . ' ';
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
    public function getHeaderContent(): string
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
        $contentType = !empty($this->attachmentList) ? 'multipart/mixed' : 'multipart/alternative';
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
    public function getEmailContent(): string
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
    public function send(): bool
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
