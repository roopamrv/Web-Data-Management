<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>

    <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <body>
        <table>
        <tr>
        <th>Subject</th>
        <th>Course</th>
        <th>Section</th>
        <th>title</th>
        <th>Instructor</th>
        </tr>

        <xsl:for-each select = "root/course[subj='MATH']">
        <tr>
            <td><xsl:value-of select="subj"/></td>
            <td><xsl:value-of select="crse"/></td>
            <td><xsl:value-of select="sect"/></td>
            <td><xsl:value-of select="title"/></td>
            <td><xsl:value-of select="instructor"/></td>
        </tr>
        </xsl:for-each>
        </table>
    </body>
    </html>
<xsl:output method="html" version="4.01" indent="yes"/>
</xsl:template>
</xsl:stylesheet>
