<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns="http://schema.slothsoft.net/farah/sitemap"
	xmlns:sfd="http://schema.slothsoft.net/farah/dictionary" xmlns:sfm="http://schema.slothsoft.net/farah/module"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/*">
		<domain name="schema.slothsoft.net" vendor="slothsoft" module="schema.slothsoft.net" ref="pages/index"
			status-active="" status-public="" sfd:languages="de-de en-us" version="1.1">
			<xsl:apply-templates select="*" />
			<page name="sitemap" ref="//slothsoft@farah/sitemap-generator" status-active="" />
		</domain>
	</xsl:template>

	<xsl:template match="*[@name='schemata']">
		<xsl:for-each select="*/*"><!-- http://schema.slothsoft.net -->
			<xsl:variable name="host" select="'http://schema.slothsoft.net'" />
			<xsl:variable name="url-base" select="concat('/', @name)" />
			<page name="{@name}" redirect=".." status-active="" status-public="">
				<xsl:for-each select="*/*">
					<xsl:variable name="url-schema" select="concat($host, $url-base, '/', @name)" />
					<page name="{@name}" title="{$url-schema}" ref="pages/schema/home" status-active="" status-public="">
						<sfm:param name="schema" value="{*/@url}" />
						<xsl:for-each select="*/*">
							<xsl:variable name="url-version" select="concat($url-schema, '/', @name)" />
							<page name="{@name}" title="{$url-version}" ref="pages/schema/documentation" status-active=""
								status-public="">
								<sfm:param name="version" value="{@name}" />
							</page>
							<file name="{@name}.xsd" title="{$url-version}" ref="{@url}" status-active="" />
						</xsl:for-each>
					</page>
				</xsl:for-each>
			</page>
		</xsl:for-each>
	</xsl:template>
</xsl:stylesheet>
				