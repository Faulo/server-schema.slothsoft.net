<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns="http://www.w3.org/1999/xhtml" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:sfs="http://schema.slothsoft.net/farah/sitemap"
    xmlns:sfm="http://schema.slothsoft.net/farah/module" xmlns:ssv="http://schema.slothsoft.net/schema/versioning" xmlns:sfd="http://schema.slothsoft.net/farah/dictionary">

    <xsl:template match="/*">
        <html>
            <head>
                <title>Slothsoft Schema Index</title>
                <style type="text/css"><![CDATA[
			]]></style>
            </head>
            <body>
                <header>
                    <h1>Slothsoft Schema Index</h1>
                </header>
                <main>
                    <p>Available namespace declarations:</p>
                    <ul>
                        <xsl:for-each select="*[@name='sites']/*/*/*">
                            <li>
                                <a href="{@uri}">
                                    <code class="namespace">
                                        <xsl:value-of select="@title" />
                                    </code>
                                </a>
                            </li>
                        </xsl:for-each>
                    </ul>
                </main>
                <footer>
                    <span sfd:dict=".">footer.copyright</span>
                    <span sfd:dict=".">footer.company</span>
                </footer>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>