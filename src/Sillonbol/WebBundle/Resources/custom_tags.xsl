<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : custom_tags.xsl
    Created on : 25 de enero de 2014, 20:19
    Author     : carlos
    Description:
        Custom tags transformations.
-->

<xsl:stylesheet
    version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
    xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"
    xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
    exclude-result-prefixes="xhtml custom image">
    <xsl:output method="html" indent="yes" encoding="UTF-8"/>

    <xsl:template match="custom[@name='youtube']">
        <div class="videoWrapper">
            <div class="video-container">
                <iframe>
                    <xsl:attribute name="width">
                        <xsl:value-of select="@custom:ancho"/>
                    </xsl:attribute>
                    <xsl:attribute name="height">
                        <xsl:value-of select="@custom:alto"/>
                    </xsl:attribute>
                    <xsl:attribute name="src">http://www.youtube.com/embed/<xsl:value-of select="@custom:codigo"/></xsl:attribute>
                    <xsl:attribute name="frameborder">0</xsl:attribute>
                    <xsl:attribute name="allowfullscreen"/>
                </iframe>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="custom[@name='externalimg']">
        <div class="externalimg">
            <img>
                <xsl:attribute name="src">
                    <xsl:value-of select="@custom:url"/> 
                </xsl:attribute>
                <xsl:attribute name="class">
                    img-responsive
                </xsl:attribute>
            </img>
             <p class="imgfooter">
                    <xsl:value-of select="@custom:pie"/>
             </p>
        </div>
    </xsl:template>
</xsl:stylesheet>
