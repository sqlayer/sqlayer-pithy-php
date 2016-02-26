# sqlayer-pithy-php
_A version of SQLayer for PHP Command Line Applications_

This version of SQLayer is called "Pithy" because it omits transactional functionality and prepared statements in favor of the speed of a direct interface, which is great for mission-critical applications, but not so great for public API's where users are providing data. If you are using SQLayer in a public environment, use *SQLayer Protracted*, which implements transactions and prepared statements.
