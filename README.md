Features
=================
Extract data from .torrent files and change them

Getting started
=================
Install using composer. Add the following to your composer.json

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Devristo/torrent"
        }
    ],
    "require": {
        "devristo/torrent": "dev-master"
    }
}
```

Reading Torrent Files
=========================

```php
use Devristo\Torrent\Torrent;

$torrent = Torrent::fromFile('ubuntu-13.10-desktop-amd64.iso.torrent');
echo $torrent->getInfoHash(false) // echoes e3811b9539cacff680e418124272177c47477157

```

Modifying Torrent Files
==========================
```php
$torrent->setPrivate(true);
$torrent->setComment("Downloaded from example.org");

file_put_contents("private-tracker.torrent", $torrent->serialize());
```