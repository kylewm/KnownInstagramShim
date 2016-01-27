# KnownInstagramShim

This is a [Known][] plugin for properly setting
syndication links on Instagram photos brought in via Micropub (using a
service like [OwnYourGram][]).

This is *emphatically not* a plugin for syndicating to
Instagram. Instagram doesn't give out access to its publish API, so we
have to fake it by posting to IG first and then reverse-syndicating
via Micropub. All this plugin does is see the incoming micropub post
and set the URL so it shows up as a u-syndication property (which also
helps Bridgy find it to backfeed likes and comments).


[Known]: https://withknown.com
[OwnYourGram]: https://ownyourgram.com
