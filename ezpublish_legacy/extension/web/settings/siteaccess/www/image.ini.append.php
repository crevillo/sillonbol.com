<?php /*

[ImageMagick]
Filters[]=strip=+profile "*" +comment
Filters[]=play=+swap -composite -gravity center extension/web/design/www/images/player_play.png


[AliasSettings]
AliasList[]=article
AliasList[]=related
AliasList[]=logo_evento
AliasList[]=participant
AliasList[]=list
AliasList[]=thumb
AliasList[]=big
AliasList[]=latest_new
AliasList[]=facebook

[article]
Reference=
Filters[]
Filters[]=geometry/scaledownonly=594;594
Filters[]=geometry/crop=594;340;0;0

[facebook]
Reference=
Filters[]
Filters[]=geometry/scaledownonly=500;300
Filters[]=geometry/crop=300;300;0;0

[thumb]
Reference=
Filters[]
Filters[]=geometry/scaledownonly=211;211
Filters[]=geometry/crop=211;85;0;0

[big]
Reference=
Filters[]
Filters[]=geometry/scalewidth=991
Filters[]=geometry/crop=991;438;0;0

[related]
Reference=
Filters[]
Filters[]=geometry/scaledownonly=105;105
Filters[]=geometry/crop=105;70;0;0

[logo_evento]
Reference=
Filters[]
Filters[]=geometry/scaledownonly=150;150

[latest_new]
Reference=
Filters[]
Filters[]=geometry/scalewidthdownonly=149

[participant]
Reference=
Filters[]
Filters[]=geometry/scaledownonly=40;40

[list]
Reference=
Filters[]
Filters[]=geometry/scaledownonly=200;2000


*/ ?>
