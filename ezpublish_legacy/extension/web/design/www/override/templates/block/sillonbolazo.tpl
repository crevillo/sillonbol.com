<article class="grid_12 block">
{ezscript_require( array( 'ezjsc::jquery', 'ezjsc::jqueryio', 'block/sbolazo/jcountdown1-4/script/jquery.jcountdown1.4.js', 'block/sbolazo/sb.sbolazo.js' ) )}
{def $events = fetch( 'ezfind', 'search', hash( 'query', '',
                                                'class_id', array( 'event' ),
                                                'filter', 'event/hora:[NOW TO *]',
                                                'sort_by', hash( 'event/hora', 'asc' ),
                                                'limit', 1
 ))}
{if $events.SearchCount|gt(0)}
{def $event = $events.SearchResult[0]}
<div class="wrapper indent-bot">
    <div class="bg-dark">
        <div class="padding-1">
            <div class="wrapper">
                <h2>Próximo sillonbolazo</h2>
            </div>
        </div>
    </div>
</div>
<div class="wrapper event-module">
    <p class="dia">{if currentdate()|datetime( 'custom', '%l' )|eq( $event.data_map.hora.content.timestamp|datetime( 'custom', '%l') )}{$event.data_map.hora.content.timestamp|datetime( 'custom', 'hoy, %H:%i h')}{else}{$event.data_map.hora.content.timestamp|datetime( 'custom', '%l, %H:%i h')}{/if}
    <p><img src={$event.data_map.logo_evento.content.logo_evento.url|ezroot} width="{$event.data_map.logo_evento.content.logo_evento.width}" height="{$event.data_map.logo_evento.content.logo_evento.heigth}" alt="" /></p>
    {*<p class="event-name">{$event.data_map.title.content}</p>*}
    {if $event.data_map.equipo1.has_content}
    <div class="match clearfix">
        <div class="part1">{if $event.data_map.foto1.has_content}<img src={$event.data_map.foto1.content.participant.url|ezroot} width="{$event.data_map.foto1.content.participant.width}" height="{$event.data_map.foto1.content.participant.height}" alt="" />{/if} {$event.data_map.equipo1.content}
        </div>
        <div class="vs">vs.</div>
         <div class="part2">{$event.data_map.equipo2.content} {if $event.data_map.foto2.has_content}<img src={$event.data_map.foto2.content.participant.url|ezroot} width="{$event.data_map.foto2.content.participant.width}" height="{$event.data_map.foto2.content.participant.height}" alt="" />{/if}
        </div>
    </div>
    <div class="info">
    {$event.data_map.text.content.output.output_text}
    </div>
    <div id="event-countdown-container"> 
        Empieza en 
        <span id="event-countdown">{$event.data_map.hora.content.timestamp}</span>
    </div>
    <p id="time"></p>
    {/if}
</div>
{undef $event}
{/if}
{undef $events}
</article>

