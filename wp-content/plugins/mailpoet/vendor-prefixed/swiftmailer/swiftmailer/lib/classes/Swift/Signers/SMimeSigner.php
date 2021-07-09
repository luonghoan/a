<?php
 namespace MailPoetVendor; if (!defined('ABSPATH')) exit; class Swift_Signers_SMimeSigner implements \MailPoetVendor\Swift_Signers_BodySigner { protected $signCertificate; protected $signPrivateKey; protected $encryptCert; protected $signThenEncrypt = \true; protected $signLevel; protected $encryptLevel; protected $signOptions; protected $encryptOptions; protected $encryptCipher; protected $extraCerts = null; protected $wrapFullMessage = \false; protected $replacementFactory; protected $headerFactory; public function __construct($signCertificate = null, $signPrivateKey = null, $encryptCertificate = null) { if (null !== $signPrivateKey) { $this->setSignCertificate($signCertificate, $signPrivateKey); } if (null !== $encryptCertificate) { $this->setEncryptCertificate($encryptCertificate); } $this->replacementFactory = \MailPoetVendor\Swift_DependencyContainer::getInstance()->lookup('transport.replacementfactory'); $this->signOptions = \PKCS7_DETACHED; $this->encryptCipher = \OPENSSL_CIPHER_AES_128_CBC; } public function setSignCertificate($certificate, $privateKey = null, $signOptions = \PKCS7_DETACHED, $extraCerts = null) { $this->signCertificate = 'file://' . \str_replace('\\', '/', \realpath($certificate)); if (null !== $privateKey) { if (\is_array($privateKey)) { $this->signPrivateKey = $privateKey; $this->signPrivateKey[0] = 'file://' . \str_replace('\\', '/', \realpath($privateKey[0])); } else { $this->signPrivateKey = 'file://' . \str_replace('\\', '/', \realpath($privateKey)); } } $this->signOptions = $signOptions; $this->extraCerts = $extraCerts ? \realpath($extraCerts) : null; return $this; } public function setEncryptCertificate($recipientCerts, $cipher = null) { if (\is_array($recipientCerts)) { $this->encryptCert = []; foreach ($recipientCerts as $cert) { $this->encryptCert[] = 'file://' . \str_replace('\\', '/', \realpath($cert)); } } else { $this->encryptCert = 'file://' . \str_replace('\\', '/', \realpath($recipientCerts)); } if (null !== $cipher) { $this->encryptCipher = $cipher; } return $this; } public function getSignCertificate() { return $this->signCertificate; } public function getSignPrivateKey() { return $this->signPrivateKey; } public function setSignThenEncrypt($signThenEncrypt = \true) { $this->signThenEncrypt = $signThenEncrypt; return $this; } public function isSignThenEncrypt() { return $this->signThenEncrypt; } public function reset() { return $this; } public function setWrapFullMessage($wrap) { $this->wrapFullMessage = $wrap; } public function signMessage(\MailPoetVendor\Swift_Message $message) { if (null === $this->signCertificate && null === $this->encryptCert) { return $this; } if ($this->signThenEncrypt) { $this->smimeSignMessage($message); $this->smimeEncryptMessage($message); } else { $this->smimeEncryptMessage($message); $this->smimeSignMessage($message); } } public function getAlteredHeaders() { return ['Content-Type', 'Content-Transfer-Encoding', 'Content-Disposition']; } protected function smimeSignMessage(\MailPoetVendor\Swift_Message $message) { if (null === $this->signCertificate) { return; } $signMessage = clone $message; $signMessage->clearSigners(); if ($this->wrapFullMessage) { $signMessage = $this->wrapMimeMessage($signMessage); } else { $this->clearAllHeaders($signMessage); $this->copyHeaders($message, $signMessage, ['Content-Type', 'Content-Transfer-Encoding', 'Content-Disposition']); } $messageStream = new \MailPoetVendor\Swift_ByteStream_TemporaryFileByteStream(); $signMessage->toByteStream($messageStream); $messageStream->commit(); $signedMessageStream = new \MailPoetVendor\Swift_ByteStream_TemporaryFileByteStream(); if (!\openssl_pkcs7_sign($messageStream->getPath(), $signedMessageStream->getPath(), $this->signCertificate, $this->signPrivateKey, [], $this->signOptions, $this->extraCerts)) { throw new \MailPoetVendor\Swift_IoException(\sprintf('Failed to sign S/Mime message. Error: "%s".', \openssl_error_string())); } $this->parseSSLOutput($signedMessageStream, $message); } protected function smimeEncryptMessage(\MailPoetVendor\Swift_Message $message) { if (null === $this->encryptCert) { return; } $encryptMessage = clone $message; $encryptMessage->clearSigners(); if ($this->wrapFullMessage) { $encryptMessage = $this->wrapMimeMessage($encryptMessage); } else { $this->clearAllHeaders($encryptMessage); $this->copyHeaders($message, $encryptMessage, ['Content-Type', 'Content-Transfer-Encoding', 'Content-Disposition']); } $messageStream = new \MailPoetVendor\Swift_ByteStream_TemporaryFileByteStream(); $encryptMessage->toByteStream($messageStream); $messageStream->commit(); $encryptedMessageStream = new \MailPoetVendor\Swift_ByteStream_TemporaryFileByteStream(); if (!\openssl_pkcs7_encrypt($messageStream->getPath(), $encryptedMessageStream->getPath(), $this->encryptCert, [], 0, $this->encryptCipher)) { throw new \MailPoetVendor\Swift_IoException(\sprintf('Failed to encrypt S/Mime message. Error: "%s".', \openssl_error_string())); } $this->parseSSLOutput($encryptedMessageStream, $message); } protected function copyHeaders(\MailPoetVendor\Swift_Message $fromMessage, \MailPoetVendor\Swift_Message $toMessage, array $headers = []) { foreach ($headers as $header) { $this->copyHeader($fromMessage, $toMessage, $header); } } protected function copyHeader(\MailPoetVendor\Swift_Message $fromMessage, \MailPoetVendor\Swift_Message $toMessage, $headerName) { $header = $fromMessage->getHeaders()->get($headerName); if (!$header) { return; } $headers = $toMessage->getHeaders(); switch ($header->getFieldType()) { case \MailPoetVendor\Swift_Mime_Header::TYPE_TEXT: $headers->addTextHeader($header->getFieldName(), $header->getValue()); break; case \MailPoetVendor\Swift_Mime_Header::TYPE_PARAMETERIZED: $headers->addParameterizedHeader($header->getFieldName(), $header->getValue(), $header->getParameters()); break; } } protected function clearAllHeaders(\MailPoetVendor\Swift_Message $message) { $headers = $message->getHeaders(); foreach ($headers->listAll() as $header) { $headers->removeAll($header); } } protected function wrapMimeMessage(\MailPoetVendor\Swift_Message $message) { $messageStream = new \MailPoetVendor\Swift_ByteStream_TemporaryFileByteStream(); $message->toByteStream($messageStream); $messageStream->commit(); $wrappedMessage = new \MailPoetVendor\Swift_MimePart($messageStream, 'message/rfc822'); $wrappedMessage->setEncoder(new \MailPoetVendor\Swift_Mime_ContentEncoder_PlainContentEncoder('7bit')); return $wrappedMessage; } protected function parseSSLOutput(\MailPoetVendor\Swift_InputByteStream $inputStream, \MailPoetVendor\Swift_Message $message) { $messageStream = new \MailPoetVendor\Swift_ByteStream_TemporaryFileByteStream(); $this->copyFromOpenSSLOutput($inputStream, $messageStream); $this->streamToMime($messageStream, $message); } protected function streamToMime(\MailPoetVendor\Swift_OutputByteStream $fromStream, \MailPoetVendor\Swift_Message $message) { list($headers, $messageStream) = $this->parseStream($fromStream); $messageHeaders = $message->getHeaders(); $encoding = ''; $messageHeaders->removeAll('Content-Transfer-Encoding'); if (isset($headers['content-transfer-encoding'])) { $encoding = $headers['content-transfer-encoding']; } $message->setEncoder(new \MailPoetVendor\Swift_Mime_ContentEncoder_NullContentEncoder($encoding)); if (isset($headers['content-disposition'])) { $messageHeaders->addTextHeader('Content-Disposition', $headers['content-disposition']); } $message->setChildren([]); $message->setBody($messageStream, $headers['content-type']); } protected function parseStream(\MailPoetVendor\Swift_OutputByteStream $emailStream) { $bufferLength = 78; $headerData = ''; $headerBodySeparator = "\r\n\r\n"; $emailStream->setReadPointer(0); while (\false !== ($buffer = $emailStream->read($bufferLength))) { $headerData .= $buffer; $headersPosEnd = \strpos($headerData, $headerBodySeparator); if (\false !== $headersPosEnd) { break; } } $headerData = \trim(\substr($headerData, 0, $headersPosEnd)); $headerLines = \explode("\r\n", $headerData); unset($headerData); $headers = []; $currentHeaderName = ''; foreach ($headerLines as $headerLine) { if (\false === \strpos($headerLine, ':')) { $headers[$currentHeaderName] .= ' ' . \trim($headerLine); continue; } $header = \explode(':', $headerLine, 2); $currentHeaderName = \strtolower($header[0]); $headers[$currentHeaderName] = \trim($header[1]); } $bodyStream = new \MailPoetVendor\Swift_ByteStream_TemporaryFileByteStream(); $emailStream->setReadPointer($headersPosEnd + \strlen($headerBodySeparator)); while (\false !== ($buffer = $emailStream->read($bufferLength))) { $bodyStream->write($buffer); } $bodyStream->commit(); return [$headers, $bodyStream]; } protected function copyFromOpenSSLOutput(\MailPoetVendor\Swift_OutputByteStream $fromStream, \MailPoetVendor\Swift_InputByteStream $toStream) { $bufferLength = 4096; $filteredStream = new \MailPoetVendor\Swift_ByteStream_TemporaryFileByteStream(); $filteredStream->addFilter($this->replacementFactory->createFilter("\r\n", "\n"), 'CRLF to LF'); $filteredStream->addFilter($this->replacementFactory->createFilter("\n", "\r\n"), 'LF to CRLF'); while (\false !== ($buffer = $fromStream->read($bufferLength))) { $filteredStream->write($buffer); } $filteredStream->flushBuffers(); while (\false !== ($buffer = $filteredStream->read($bufferLength))) { $toStream->write($buffer); } $toStream->commit(); } } 