php-simplemail-receiver
=======================

Simple library to connect to a POP3/IMAP/NNTP mail server and receive mails (with attachements)

Necessary Settings as Input:

 * host: The address of the mail server
 * port: The port to access to the mail server
 * username: The username of the account
 * password: The password of the account
 * protocol: Protocol used to connect with the mail server. Options:
    - imap
    - pop3
    - nntp
 * ssl: Secure Socket Layer. true or false
 * folder: The specific folder that belongs to the mailbox