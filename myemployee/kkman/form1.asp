<!-- #include file="connectSQL.asp"-->

<%
SerialNo=Request("SerialNo")

	Set Listemploy_MRst = Server.CreateObject("ADODB.Recordset")
 	SQL = "sp_Listemploy_M '" & SerialNo & "'"  
    Set Listemploy_MRst = conn.execute(SQL) 
	
	BugNo=Trim(Listemploy_MRst("BugNo"))

	Set Listemploy_DRst = Server.CreateObject("ADODB.Recordset")
 	SQL = "sp_Listemploy_D '" & SerialNo & "'"  
    Set Listemploy_DRst = conn.execute(SQL) 
	
	if int(Listemploy_DRst("count"))<5 then countperson=4 else countperson=int(Listemploy_DRst("count")) end if

%>

<%
       		 	Set Rs = Server.CreateObject("ADODB.Recordset")
       		 	strSQL = "select * from [personnelcommon].[dbo].[vi_buget] where bugetno='" & BugNo & "'" 
      		 	
				Set Rs = Conn.Execute(strSQL)        
       		 	if not Rs.EOF then
					bugetno=trim(Rs("bugetno"))
					bugname=trim(Rs("bugname"))
					leader=trim(Rs("leader"))
					deadline=trim(Rs("deadline"))
					start=trim(Rs("start"))
					delay=trim(Rs("delay"))
					depname=trim(Rs("Depname"))
				end if
				
			 	
			 	
       		%>
<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=big5">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 14">
<meta name=Originator content="Microsoft Word 14">
<link rel=File-List href="from1.files/filelist.xml">
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>Emiya</o:Author>
  <o:LastAuthor>���V��</o:LastAuthor>
  <o:Revision>2</o:Revision>
  <o:TotalTime>85</o:TotalTime>
  <o:LastPrinted>2011-12-02T07:13:00Z</o:LastPrinted>
  <o:Created>2012-02-01T08:05:00Z</o:Created>
  <o:LastSaved>2012-02-01T08:05:00Z</o:LastSaved>
  <o:Pages>1</o:Pages>
  <o:Words>126</o:Words>
  <o:Characters>724</o:Characters>
  <o:Lines>6</o:Lines>
  <o:Paragraphs>1</o:Paragraphs>
  <o:CharactersWithSpaces>849</o:CharactersWithSpaces>
  <o:Version>14.00</o:Version>
 </o:DocumentProperties>
 <o:OfficeDocumentSettings>
  <o:AllowPNG/>
 </o:OfficeDocumentSettings>
</xml><![endif]-->
<link rel=themeData href="from1.files/themedata.thmx">
<link rel=colorSchemeMapping href="from1.files/colorschememapping.xml">
<!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:SpellingState>Clean</w:SpellingState>
  <w:GrammarState>Clean</w:GrammarState>
  <w:TrackMoves>false</w:TrackMoves>
  <w:TrackFormatting/>
  <w:PunctuationKerning/>
  <w:DrawingGridHorizontalSpacing>6 �I</w:DrawingGridHorizontalSpacing>
  <w:DisplayHorizontalDrawingGridEvery>0</w:DisplayHorizontalDrawingGridEvery>
  <w:DisplayVerticalDrawingGridEvery>2</w:DisplayVerticalDrawingGridEvery>
  <w:ValidateAgainstSchemas/>
  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
  <w:DoNotPromoteQF/>
  <w:LidThemeOther>EN-US</w:LidThemeOther>
  <w:LidThemeAsian>ZH-TW</w:LidThemeAsian>
  <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>
  <w:Compatibility>
   <w:SpaceForUL/>
   <w:BalanceSingleByteDoubleByteWidth/>
   <w:DoNotLeaveBackslashAlone/>
   <w:ULTrailSpace/>
   <w:DoNotExpandShiftReturn/>
   <w:AdjustLineHeightInTable/>
   <w:BreakWrappedTables/>
   <w:SnapToGridInCell/>
   <w:WrapTextWithPunct/>
   <w:UseAsianBreakRules/>
   <w:DontGrowAutofit/>
   <w:DontUseIndentAsNumberingTabStop/>
   <w:FELineBreak11/>
   <w:WW11IndentRules/>
   <w:DontAutofitConstrainedTables/>
   <w:AutofitLikeWW11/>
   <w:HangulWidthLikeWW11/>
   <w:UseNormalStyleForList/>
   <w:DontVertAlignCellWithSp/>
   <w:DontBreakConstrainedForcedTables/>
   <w:DontVertAlignInTxbx/>
   <w:Word11KerningPairs/>
   <w:CachedColBalance/>
   <w:UseFELayout/>
  </w:Compatibility>
  <m:mathPr>
   <m:mathFont m:val="Cambria Math"/>
   <m:brkBin m:val="before"/>
   <m:brkBinSub m:val="&#45;-"/>
   <m:smallFrac m:val="off"/>
   <m:dispDef/>
   <m:lMargin m:val="0"/>
   <m:rMargin m:val="0"/>
   <m:defJc m:val="centerGroup"/>
   <m:wrapIndent m:val="1440"/>
   <m:intLim m:val="subSup"/>
   <m:naryLim m:val="undOvr"/>
  </m:mathPr></w:WordDocument>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:LatentStyles DefLockedState="false" DefUnhideWhenUsed="true"
  DefSemiHidden="true" DefQFormat="false" DefPriority="99"
  LatentStyleCount="267">
  <w:LsdException Locked="false" Priority="0" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Normal"/>
  <w:LsdException Locked="false" Priority="9" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="heading 1"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 2"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 3"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 4"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 5"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 6"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 7"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 8"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 9"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 1"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 2"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 3"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 4"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 5"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 6"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 7"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 8"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 9"/>
  <w:LsdException Locked="false" Priority="35" QFormat="true" Name="caption"/>
  <w:LsdException Locked="false" Priority="10" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Title"/>
  <w:LsdException Locked="false" Priority="1" Name="Default Paragraph Font"/>
  <w:LsdException Locked="false" Priority="11" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Subtitle"/>
  <w:LsdException Locked="false" Priority="22" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Strong"/>
  <w:LsdException Locked="false" Priority="20" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Emphasis"/>
  <w:LsdException Locked="false" Priority="59" SemiHidden="false"
   UnhideWhenUsed="false" Name="Table Grid"/>
  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Placeholder Text"/>
  <w:LsdException Locked="false" Priority="1" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="No Spacing"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 1"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 1"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 1"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 1"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 1"/>
  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Revision"/>
  <w:LsdException Locked="false" Priority="34" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="List Paragraph"/>
  <w:LsdException Locked="false" Priority="29" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Quote"/>
  <w:LsdException Locked="false" Priority="30" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Intense Quote"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 1"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 1"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 1"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 1"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 1"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 1"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 2"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 2"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 2"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 2"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 2"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 2"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 2"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 2"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 3"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 3"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 3"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 3"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 3"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 3"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 3"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 3"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 4"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 4"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 4"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 4"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 4"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 4"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 4"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 4"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 5"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 5"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 5"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 5"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 5"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 5"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 5"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 5"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 6"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 6"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 6"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 6"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 6"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 6"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 6"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 6"/>
  <w:LsdException Locked="false" Priority="19" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Subtle Emphasis"/>
  <w:LsdException Locked="false" Priority="21" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Intense Emphasis"/>
  <w:LsdException Locked="false" Priority="31" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Subtle Reference"/>
  <w:LsdException Locked="false" Priority="32" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Intense Reference"/>
  <w:LsdException Locked="false" Priority="33" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Book Title"/>
  <w:LsdException Locked="false" Priority="37" Name="Bibliography"/>
  <w:LsdException Locked="false" Priority="39" QFormat="true" Name="TOC Heading"/>
 </w:LatentStyles>
</xml><![endif]-->
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:�s�ө���;
	panose-1:2 2 5 0 0 0 0 0 0 0;
	mso-font-alt:PMingLiU;
	mso-font-charset:136;
	mso-generic-font-family:roman;
	mso-font-pitch:variable;
	mso-font-signature:-1610611969 684719354 22 0 1048577 0;}
@font-face
	{font-family:�s�ө���;
	panose-1:2 2 5 0 0 0 0 0 0 0;
	mso-font-alt:PMingLiU;
	mso-font-charset:136;
	mso-generic-font-family:roman;
	mso-font-pitch:variable;
	mso-font-signature:-1610611969 684719354 22 0 1048577 0;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-520092929 1073786111 9 0 415 0;}
@font-face
	{font-family:�з���;
	panose-1:3 0 5 9 0 0 0 0 0 0;
	mso-font-charset:136;
	mso-generic-font-family:script;
	mso-font-pitch:fixed;
	mso-font-signature:3 135135232 22 0 1048577 0;}
@font-face
	{font-family:"\@�з���";
	panose-1:3 0 5 9 0 0 0 0 0 0;
	mso-font-charset:136;
	mso-generic-font-family:script;
	mso-font-pitch:fixed;
	mso-font-signature:3 135135232 22 0 1048577 0;}
@font-face
	{font-family:"\@�s�ө���";
	panose-1:2 2 5 0 0 0 0 0 0 0;
	mso-font-charset:136;
	mso-generic-font-family:roman;
	mso-font-pitch:variable;
	mso-font-signature:-1610611969 684719354 22 0 1048577 0;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-parent:"";
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:none;
	font-size:10pt;
	mso-bidi-font-size:11.0pt;
	font-family:"Calibri", "sans-serif";
	mso-fareast-font-family:�s�ө���;
	mso-bidi-font-family:"Times New Roman";
	mso-font-kerning:1.0pt;
}
p.MsoHeader, li.MsoHeader, div.MsoHeader
	{mso-style-priority:99;
	mso-style-link:"���� �r��";
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:none;
	tab-stops:center 207.65pt right 415.3pt;
	layout-grid-mode:char;
	font-size:10.0pt;
	font-family:"Calibri","sans-serif";
	mso-fareast-font-family:�s�ө���;
	mso-bidi-font-family:"Times New Roman";
	mso-font-kerning:1.0pt;}
p.MsoFooter, li.MsoFooter, div.MsoFooter
	{mso-style-priority:99;
	mso-style-link:"���� �r��";
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:none;
	tab-stops:center 207.65pt right 415.3pt;
	layout-grid-mode:char;
	font-size:10.0pt;
	font-family:"Calibri","sans-serif";
	mso-fareast-font-family:�s�ө���;
	mso-bidi-font-family:"Times New Roman";
	mso-font-kerning:1.0pt;}
p.MsoListParagraph, li.MsoListParagraph, div.MsoListParagraph
	{
	mso-style-priority:34;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:24pt;
	margin-bottom:0.0001pt;
	mso-para-margin-top:0cm;
	mso-para-margin-right:0cm;
	mso-para-margin-bottom:0cm;
	mso-para-margin-left:2.0gd;
	mso-para-margin-bottom:.0001pt;
	mso-pagination:none;
	font-size:10pt;
	mso-bidi-font-size:11.0pt;
	font-family:"Calibri","sans-serif";
	mso-fareast-font-family:�s�ө���;
	mso-bidi-font-family:"Times New Roman";
	mso-font-kerning:1.0pt;
}
span.a
	{mso-style-name:"���� �r��";
	mso-style-priority:99;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-parent:"";
	mso-style-link:����;
	mso-ansi-font-size:10.0pt;
	mso-bidi-font-size:10.0pt;}
span.a0
	{mso-style-name:"���� �r��";
	mso-style-priority:99;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-parent:"";
	mso-style-link:����;
	mso-ansi-font-size:10.0pt;
	mso-bidi-font-size:10.0pt;}
span.GramE
	{mso-style-name:"";
	mso-gram-e:yes;}
.MsoChpDefault
	{mso-style-type:export-only;
	mso-default-props:yes;
	mso-ascii-font-family:Calibri;
	mso-fareast-font-family:�s�ө���;
	mso-hansi-font-family:Calibri;}
 /* Page Definitions */
 @page
	{mso-page-border-surround-header:no;
	mso-page-border-surround-footer:no;
	/*mso-footnote-separator:url("from1.files/header.htm") fs;
	mso-footnote-continuation-separator:url("from1.files/header.htm") fcs;
	mso-endnote-separator:url("from1.files/header.htm") es;
	mso-endnote-continuation-separator:url("from1.files/header.htm") ecs;*/}
@page WordSection1
	{size:841.9pt 595.3pt;
	mso-page-orientation:landscape;
	margin:18.0pt 72.0pt 9.0pt 72.0pt;
	mso-header-margin:42.55pt;
	mso-footer-margin:49.6pt;
	mso-paper-source:0;
	layout-grid:18.0pt;}
div.WordSection1
	{page:WordSection1;}
 /* List Definitions */
 @list l0
	{mso-list-id:402027765;
	mso-list-type:hybrid;
	mso-list-template-ids:-1527854840 1167372742 67698713 67698715 67698703 67698713 67698715 67698703 67698713 67698715;}
@list l0:level1
	{mso-level-tab-stop:none;
	mso-level-number-position:left;
	margin-left:18.0pt;
	text-indent:-18.0pt;}
@list l0:level2
	{mso-level-number-format:ideograph-traditional;
	mso-level-text:%2�B;
	mso-level-tab-stop:none;
	mso-level-number-position:left;
	margin-left:48.0pt;
	text-indent:-24.0pt;}
@list l0:level3
	{mso-level-number-format:roman-lower;
	mso-level-tab-stop:none;
	mso-level-number-position:right;
	margin-left:72.0pt;
	text-indent:-24.0pt;}
@list l0:level4
	{mso-level-tab-stop:none;
	mso-level-number-position:left;
	margin-left:96.0pt;
	text-indent:-24.0pt;}
@list l0:level5
	{mso-level-number-format:ideograph-traditional;
	mso-level-text:%5�B;
	mso-level-tab-stop:none;
	mso-level-number-position:left;
	margin-left:120.0pt;
	text-indent:-24.0pt;}
@list l0:level6
	{mso-level-number-format:roman-lower;
	mso-level-tab-stop:none;
	mso-level-number-position:right;
	margin-left:144.0pt;
	text-indent:-24.0pt;}
@list l0:level7
	{mso-level-tab-stop:none;
	mso-level-number-position:left;
	margin-left:168.0pt;
	text-indent:-24.0pt;}
@list l0:level8
	{mso-level-number-format:ideograph-traditional;
	mso-level-text:%8�B;
	mso-level-tab-stop:none;
	mso-level-number-position:left;
	margin-left:192.0pt;
	text-indent:-24.0pt;}
@list l0:level9
	{mso-level-number-format:roman-lower;
	mso-level-tab-stop:none;
	mso-level-number-position:right;
	margin-left:216.0pt;
	text-indent:-24.0pt;}
ol
	{margin-bottom:0cm;}
ul
	{margin-bottom:0cm;}
-->
</style>
<!--[if gte mso 10]>
<style>
 /* Style Definitions */
 table.MsoNormalTable
	{mso-style-name:��椺��;
	mso-tstyle-rowband-size:0;
	mso-tstyle-colband-size:0;
	mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-parent:"";
	mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
	mso-para-margin:0cm;
	mso-para-margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Calibri","sans-serif";
	mso-bidi-font-family:"Times New Roman";}
table.MsoTableGrid
	{mso-style-name:����u;
	mso-tstyle-rowband-size:0;
	mso-tstyle-colband-size:0;
	mso-style-priority:59;
	mso-style-unhide:no;
	border:solid windowtext 1.0pt;
	mso-border-alt:solid windowtext .5pt;
	mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
	mso-border-insideh:.5pt solid windowtext;
	mso-border-insidev:.5pt solid windowtext;
	mso-para-margin:0cm;
	mso-para-margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Calibri","sans-serif";
	mso-bidi-font-family:"Times New Roman";}
</style>
<![endif]--><!--[if gte mso 9]><xml>
 <o:shapedefaults v:ext="edit" spidmax="2049"/>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <o:shapelayout v:ext="edit">
  <o:idmap v:ext="edit" data="1"/>
 </o:shapelayout></xml><![endif]-->
</head>

<body lang=ZH-TW style='tab-interval:24.0pt;text-justify-trim:punctuation'>

<div class=WordSection1 style='layout-grid:18.0pt'>

<p class=MsoNormal align=center style='text-align:center;line-height:16.0pt;
mso-line-height-rule:exactly'><span style='font-size:16.0pt;font-family:�з���'>��ߥ�q�j�ǭp�e�ݥ��H�����{�ɤu<span
class=GramE>�Юֳ�</span><span lang=EN-US><o:p></o:p></span></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-size:10.0pt;font-family:�з���'>���s���G<%=Listemploy_MRst("SerialNo")%></span></p></p>

<p class=MsoNormal style='line-height:18.0pt;mso-line-height-rule:exactly'><span
lang=EN-US style='font-size:16.0pt;font-family:�з���'><span
style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span><span
style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;</span></span><span
style='mso-bidi-font-size:12.0pt;font-family:�з���'>������G<span lang=EN-US><span
style='mso-spacerun:yes'>&nbsp;<%=year(Trim(Listemploy_MRst("createtime")))-1911%>&nbsp; </span></span>�~<span lang=EN-US><span
style='mso-spacerun:yes'>&nbsp;<%=month(Trim(Listemploy_MRst("createtime")))%>&nbsp; </span></span>��<span lang=EN-US><span
style='mso-spacerun:yes'>&nbsp;<%=day(Trim(Listemploy_MRst("createtime")))%> </span></span>�� <span lang=EN-US><o:p></o:p></span></span></p>

<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 width=981
 style='width:735.45pt;border-collapse:collapse;border:none;mso-border-alt:
 solid windowtext .5pt;mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
 mso-border-insideh:.5pt solid windowtext;mso-border-insidev:.5pt solid windowtext'>
 <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;page-break-inside:avoid;
  height:28.65pt'>
  <td width=147 colspan=2 style='width:109.9pt;border:solid windowtext 1.0pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�p�e������<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=253 colspan=2 style='width:109.9pt;border:solid windowtext 1.0pt;
  border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  lang=EN-US style='mso-bidi-font-size:12.0pt;font-family:�з���'><o:p><%=depname%>&nbsp;</o:p></span></p>
  </td>
  <td width=147 colspan=2 style='width:50.0pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>�p�e�D���H</span></p>
  </td>
  <td width=253 colspan=2 style='width:105.5pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  lang=EN-US style='mso-bidi-font-size:12.0pt;font-family:�з���'><%=leader%>&nbsp;</span></p>
  </td>
  <td width=236 colspan=3 style='width:200.3pt;border:solid windowtext 1.0pt;
  border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�p�e�W�٤νs��<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=345 colspan=4 valign=top style='width:258.75pt;border:solid windowtext 1.0pt;
  border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal><span lang=EN-US style='mso-bidi-font-size:12.0pt;
  font-family:�з���'><o:p><%=BugNo%>&nbsp;&nbsp;<%=bugname%></o:p>
  </span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:1;page-break-inside:avoid;height:28.65pt'>
  <td width=147 colspan=2 style='width:109.9pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�p�e�������<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=253 colspan=6 style='width:189.5pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  lang=EN-US style='mso-bidi-font-size:12.0pt;font-family:�з���'><o:p><%=start%>&nbsp;-&nbsp;<%if delay<>"" then response.Write delay else response.Write deadline end if%></o:p></span></p>
  </td>
  <td width=236 colspan=3 style='width:177.3pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�֩w�H��(�~��)�O���B<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=345 colspan=4 valign=top style='width:258.75pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal><span lang=EN-US style='mso-bidi-font-size:12.0pt;
  font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:2;page-break-inside:avoid;height:28.65pt'>
  <td width=147 colspan=2 style='width:109.9pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�p�e�e�U/�ɧU���</span></p>
  </td>
  <td width=253 colspan=6 style='width:189.5pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  lang=EN-US style='mso-bidi-font-size:12.0pt;font-family:�з���'><%=Trim(Listemploy_MRst("GiveUnit"))%></span></p>
  </td>
  <td width=236 colspan=3 style='width:177.3pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='line-height:12.0pt;layout-grid-mode:char;
  mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;
  font-family:�з���'>�O�_�����ݥD�ޤ��t����<span lang=EN-US><o:p></o:p></span></span></p>
  <p class=MsoNormal style='line-height:12.0pt;layout-grid-mode:char;
  mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;
  font-family:�з���'>�T���˵�������ˡB�ÿ�<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=345 colspan=4 style='width:258.75pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='line-height:16.0pt;mso-line-height-rule:exactly'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>���O<span lang=EN-US><span
  style='mso-spacerun:yes'>&nbsp; </span><span
  style='mso-spacerun:yes'>&nbsp;</span><span
  style='mso-spacerun:yes'>&nbsp;</span></span><span class=GramE>�i�_</span><span
  lang=EN-US>(</span>�j�׶i�ηs�i�p�e�D���H�Φ@�P�p�e�D���H���t���ΤT�˵�������ˡB�ÿ�<span lang=EN-US>)<o:p></o:p></span></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:3;height:25.5pt'>
  <td width=79 style='width:75.4pt;border:solid windowtext 1.0pt;border-top:
  none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�ݥ�¾��<span lang=EN-US><o:p></o:p></span></span></p>
  <p class=MsoNormal align=center style='text-align:center'></p>
  </td>
  <td width=168 colspan=4 valign=top style='width:186.0pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph;
  line-height:12.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>���u�N����¾��</span><span
  style='font-size:10.0pt;font-family:�з���'>�]�{¾�H���^<span lang=EN-US>
  <o:p></o:p></span></span></p>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph;
  line-height:12.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�Ǹ�</span><span
  style='font-size:10.0pt;font-family:�з���'>�]�ǥ͡^</span><span lang=EN-US
  style='mso-bidi-font-size:12.0pt;font-family:�з���'><o:p></o:p></span></p>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph;
  line-height:12.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�����Ҧr��</span><span
  style='font-size:10.0pt;font-family:�з���'>�]�ե~�H�h�^</span><span lang=EN-US
  style='mso-bidi-font-size:12.0pt;font-family:�з���'><o:p></o:p></span></p>
  </td>
  <td width=72 style='width:54.0pt;border-top:none;border-left:none;border-bottom:
  solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:
  solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�m �W<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=80 colspan=2 style='width:60.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>���δ���<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=78 style='width:78.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>���<br>����</p>
  </td>
  <td width=80 style='width:70.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�C��u�@<span lang=EN-US><o:p></o:p></span></span></p>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�ɼƤW��<span lang=EN-US>
  <o:p></o:p></span></span></p>
  </td>
  <td width=78 style='width:35.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�u�@<span lang=EN-US><o:p></o:p></span></span></p>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>���~<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=79 style='width:59.1pt;border-top:none;border-left:none;border-bottom:
  solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:
  solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>���U�椸<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=85 style='width:63.9pt;border-top:none;border-left:none;border-bottom:
  solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:
  solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�����B<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=146 style='width:109.8pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph;line-height:10.0pt;mso-line-height-rule:exactly'><span style='font-size:11.0pt;font-family:�з���'>�O�_�㦳<br>��������</span></p>
  </td>
  <td width=113 style='width:3.0cm;border-top:none;border-left:none;border-bottom:
  solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:
  solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>��<span lang=EN-US><span
  style='mso-spacerun:yes'>&nbsp; </span></span><span class=GramE>��</span><span
  lang=EN-US><o:p></o:p></span></span></p>
  </td>
 </tr>
 
 
  <% For i=1 to  countperson
	'��ե~�H�h���
	Set ListEmpData_OutSideRst = Server.CreateObject("ADODB.Recordset")
	SQL = "sp_ListEmpData_OutSide '" &Trim(Listemploy_DRst("EID"))& "'"
	Set ListEmpData_OutSideRst = conn.execute(SQL)
	
	IF ListEmpData_OutSideRst.eof THEN
		OutSideUnit=""
		OutSideTitle=""
	ELSE
		Set ListOutSideTitle = Server.CreateObject("ADODB.Recordset")
		SQL = "SELECT * FROM OutSideTitle WHERE TitleCode='" &Trim(ListEmpData_OutSideRst("OutSideTitle"))& "'"
		Set ListOutSideTitle = conn.execute(SQL)
		OutSideUnit=Trim(ListEmpData_OutSideRst("OutSideUnit"))
		IF NOT ListOutSideTitle.eof THEN
			OutSideTitle=Trim(ListOutSideTitle("TitleName"))
		ELSE
			OutSideTitle=""
		END IF
	END IF
  %>
 <tr style='mso-yfti-irow:4;height:15.25pt'>
  <td width=79 valign=top style='width:59.4pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:15.25pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:10pt; font-family:�з���'><o:p><%if int(Listemploy_DRst("count"))>= i then response.Write  Listemploy_DRst("TitleName") end if%>&nbsp;</o:p></span></p>
  </td>
  <td width=168 colspan=4 valign=top style='width:126.0pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:15.25pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:10pt; font-family:�з���'><o:p><%if int(Listemploy_DRst("count"))>= i then response.Write  Listemploy_DRst("IdCode")&Listemploy_DRst("Etitlename")&" "&OutSideTitle end if%>&nbsp;</o:p></span></p>
  </td>
  <td width=72 valign=top style='width:54.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:15.25pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:10pt; font-family:�з���'><o:p><%if int(Listemploy_DRst("count"))>= i then response.Write  Listemploy_DRst("Cname") end if%>&nbsp;</o:p></span></p>
  </td>
  <td width=80 colspan=2 valign=top style='width:60.0pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:15.25pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:10pt; font-family:�з���'><o:p><%if int(Listemploy_DRst("count"))>= i then response.Write  year(trim(Listemploy_DRst("Empdate")))-1911&"/"&month(trim(Listemploy_DRst("Empdate")))&"/"&day(trim(Listemploy_DRst("Empdate")))&"&nbsp;-&nbsp;"&year(trim(Listemploy_DRst("Enddate")))-1911&"/"&month(trim(Listemploy_DRst("Enddate")))&"/"&day(trim(Listemploy_DRst("Enddate")))end if%></o:p></span></p>
  </td>
  <td width=78 style='width:78.25pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:15.25pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:9pt; font-family:�з���'><%if int(Listemploy_DRst("count"))>= i then response.Write  Listemploy_DRst("JobItemName") end if%></span></p>
  </td>
  <td width=80 valign=top style='width:70.95pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:15.25pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:10pt; font-family:�з���'>
  <%if int(Listemploy_DRst("count"))>= i then 
		if Trim(Listemploy_DRst("TitleCode"))="4" and Trim(Listemploy_DRst("WorkingHours"))<>"" then
			response.Write Listemploy_DRst("WorkingHours")
		elseif Trim(Listemploy_DRst("TitleCode"))="4" then
			response.Write "�H��ڤu�ɭp��"
		end if
	end if%>&nbsp;</span></p>
  </td>
  <td width=78 valign=top style='width:35.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:15.25pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:10pt; font-family:�з���'><o:p><%if int(Listemploy_DRst("count"))>= i then response.Write  Listemploy_DRst("Pay") end if%>&nbsp;</o:p></span></p>
  </td>
  <td width=79 valign=top style='width:59.1pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:15.25pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:10pt; font-family:�з���'><o:p><%if int(Listemploy_DRst("count"))>= i then response.Write  Listemploy_DRst("AwardUnit") end if%>&nbsp;</o:p></span></p>
  </td>
  <td width=85 valign=top style='width:63.9pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:15.25pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:10pt; font-family:�з���'><o:p><%if int(Listemploy_DRst("count"))>= i then response.Write  Listemploy_DRst("MonthlyExpenses") end if%>&nbsp;</o:p></span></p>
  </td>
  <td width=146 valign=top style='width:109.8pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:15.25pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph;
  line-height:12.0pt;mso-line-height-rule:exactly'><span style='mso-bidi-font-size:
  9.0pt;font-family:�з���'><%if trim(Listemploy_DRst("IsAboriginal"))="1" and int(Listemploy_DRst("count"))>= i then response.Write "�i" else response.Write "��" end if%>�O</span><span
  lang=EN-US style='mso-bidi-font-size:12.0pt;font-family:�з���'>
  </span><span class=GramE><span style='mso-bidi-font-size:9.0pt;font-family:
  �з���'><%if trim(Listemploy_DRst("IsAboriginal"))="0" and int(Listemploy_DRst("count"))>= i then response.Write "�i" else response.Write "��" end if%></span></span><span style='mso-bidi-font-size:9.0pt;font-family:�з���'>�_
  <!-->�Ҷq�Ӹ���D�A����ܬO�_���߻�ê��<-->
  </span></p>
  </td>
  <td width=113 valign=top style='width:3.0cm;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:15.25pt'><p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph;
  line-height:9.0pt;mso-line-height-rule:exactly'>
<span
  lang=EN-US style='font-size:8pt; font-family:�з���'><o:p>
  <%if int(Listemploy_DRst("count"))>= i then 
  response.Write  Listemploy_DRst("memo")
	if Trim(Listemploy_DRst("memo"))<>"" THEN Response.Write "<br>" END IF
  
	  if len(trim(Listemploy_DRst("IdCode")))>6 then  
			SQL ="[dbo].[sp_getIdno] '"&trim(Listemploy_DRst("IdCode"))&"'"
			Set GetIdnoRst = conn.execute(SQL)
		
			SQL ="SELECT *  fROM [EmpData] where [iDNo]='"&trim(GetIdnoRst("idno"))&"' and rtrim([iDNo])<>''"
			'response.Write SQL
			Set checkDouRst = conn.execute(SQL)
			SQL ="SELECT * from [vi_PersonnelPopular] where [idno]='"&trim(GetIdnoRst("idno"))&"'"
			Set checkLeaveTime = conn.execute(SQL)
			if not checkDouRst.EOF then  
				if checkLeaveTime("�b��¾���A")="�b¾" then
					response.Write " (�㦳�M�������A�u���G"&checkDouRst("EmpNo")&")<br>" 
				end if
			end if						
	  end if
   
  end if
	if int(Listemploy_DRst("count"))>= i then 
		IF OutSideUnit<>"" THEN
			Response.Write "�A�Ⱦ����G"&OutSideUnit&""
		END IF
	end if
  %>
  
  </o:p></span></p>
  </td>
 </tr>
 <%If int(Listemploy_DRst("count"))> i  Then 
	 		Listemploy_DRst.MoveNext 
	  End If%>
 <%next%>
 <!--
 <tr style='mso-yfti-irow:5;height:34.0pt'>
  <td width=79 valign=top style='width:59.4pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:34.0pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=168 colspan=2 valign=top style='width:126.0pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.0pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=72 valign=top style='width:54.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.0pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=80 colspan=2 valign=top style='width:60.0pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.0pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=80 valign=top style='width:59.95pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.0pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=78 valign=top style='width:58.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.0pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=79 valign=top style='width:59.1pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.0pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=85 valign=top style='width:63.9pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.0pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=146 valign=top style='width:109.8pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.0pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>���O</span><span
  style='font-size:9.0pt;font-family:�з���'>�]���˪��ҩ����^</span><span
  style='font-size:10.0pt;font-family:�з���'> </span><span lang=EN-US
  style='mso-bidi-font-size:12.0pt;font-family:�з���'><span
  style='mso-spacerun:yes'>&nbsp;&nbsp;</span><br>
  </span><span class=GramE><span style='mso-bidi-font-size:12.0pt;font-family:
  �з���'>��</span></span><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>�_</span><span
  lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p></o:p></span></p>
  </td>
  <td width=113 valign=top style='width:3.0cm;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.0pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>���s�u<span
  lang=EN-US><o:p></o:p></span></span></p>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>����u</span><span
  lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:6;height:18.7pt'>
  <td width=79 valign=top style='width:59.4pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:18.7pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=168 colspan=2 valign=top style='width:126.0pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:18.7pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=72 valign=top style='width:54.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:18.7pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=80 colspan=2 valign=top style='width:60.0pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:18.7pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=80 valign=top style='width:59.95pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:18.7pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=78 valign=top style='width:58.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:18.7pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=79 valign=top style='width:59.1pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:18.7pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=85 valign=top style='width:63.9pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:18.7pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=146 valign=top style='width:109.8pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:18.7pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>���O</span><span
  style='font-size:9.0pt;font-family:�з���'>�]���˪��ҩ����^</span><span
  style='font-size:10.0pt;font-family:�з���'> </span><span lang=EN-US
  style='mso-bidi-font-size:12.0pt;font-family:�з���'><span
  style='mso-spacerun:yes'>&nbsp;&nbsp;</span><br>
  </span><span class=GramE><span style='mso-bidi-font-size:12.0pt;font-family:
  �з���'>��</span></span><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>�_</span><span
  lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p></o:p></span></p>
  </td>
  <td width=113 valign=top style='width:3.0cm;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:18.7pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>���s�u<span
  lang=EN-US><o:p></o:p></span></span></p>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>����u</span><span
  lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:7;height:25.6pt'>
  <td width=79 valign=top style='width:59.4pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:double windowtext 1.5pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  mso-border-bottom-alt:double windowtext 1.5pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:25.6pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=168 colspan=2 valign=top style='width:126.0pt;border-top:none;
  border-left:none;border-bottom:double windowtext 1.5pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-bottom-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:25.6pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=72 valign=top style='width:54.0pt;border-top:none;border-left:none;
  border-bottom:double windowtext 1.5pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-bottom-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:25.6pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=80 colspan=2 valign=top style='width:60.0pt;border-top:none;
  border-left:none;border-bottom:double windowtext 1.5pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-bottom-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:25.6pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=80 valign=top style='width:59.95pt;border-top:none;border-left:
  none;border-bottom:double windowtext 1.5pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-bottom-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:25.6pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=78 valign=top style='width:58.25pt;border-top:none;border-left:
  none;border-bottom:double windowtext 1.5pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-bottom-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:25.6pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=79 valign=top style='width:59.1pt;border-top:none;border-left:none;
  border-bottom:double windowtext 1.5pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-bottom-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:25.6pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=85 valign=top style='width:63.9pt;border-top:none;border-left:none;
  border-bottom:double windowtext 1.5pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-bottom-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:25.6pt'>
  <p class=MsoNormal><span lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=146 valign=top style='width:109.8pt;border-top:none;border-left:
  none;border-bottom:double windowtext 1.5pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-bottom-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:25.6pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>���O</span><span
  style='font-size:9.0pt;font-family:�з���'>�]���˪��ҩ����^</span><span
  style='font-size:10.0pt;font-family:�з���'> </span><span lang=EN-US
  style='mso-bidi-font-size:12.0pt;font-family:�з���'><span
  style='mso-spacerun:yes'>&nbsp;&nbsp;</span><br>
  </span><span class=GramE><span style='mso-bidi-font-size:12.0pt;font-family:
  �з���'>��</span></span><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>�_</span><span
  lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p></o:p></span></p>
  </td>
  <td width=113 valign=top style='width:3.0cm;border-top:none;border-left:none;
  border-bottom:double windowtext 1.5pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-bottom-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:25.6pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>���s�u<span
  lang=EN-US><o:p></o:p></span></span></p>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>����u</span><span
  lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p></o:p></span></p>
  </td>
 </tr>-->
 <tr style='mso-yfti-irow:8;height:24.0pt'>
  <td width=79 colspan=2 style='width:59.4pt;border:solid windowtext 1.0pt;border-top:
  none;mso-border-top-alt:double windowtext 1.5pt;mso-border-alt:solid windowtext .5pt;
  mso-border-top-alt:double windowtext 1.5pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:24.0pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�~�ȳ��<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=282 colspan=4 style='width:211.8pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:double windowtext 1.5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-top-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:24.0pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�ӿ�H<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=274 colspan=4 style='width:205.5pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:double windowtext 1.5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-top-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:24.0pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>�p�e�D���H</span><span
  lang=EN-US style='font-size:16.0pt;font-family:�з���'><o:p></o:p></span></p>
  </td>
  <td width=345 colspan=5 valign=top style='width:258.75pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:double windowtext 1.5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-top-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:24.0pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>���D��<span
  lang=EN-US><o:p></o:p></span></span></p>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>�]�t�ҩΤ��ߥD�ޡ^<span
  lang=EN-US><o:p></o:p></span></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:9;height:34.85pt'>
  <td width=79 colspan=2 style='width:59.4pt;border:solid windowtext 1.0pt;border-top:
  none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:34.85pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>�|��Χ��<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=282 colspan=4 style='width:211.8pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.85pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>�D�p��</span><span
  lang=EN-US style='font-size:16.0pt;font-family:�з���'>
  <o:p></o:p></span></p>
  </td>
  <td width=274 colspan=4 style='width:205.5pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.85pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>�H�ƫ�</span><span
  lang=EN-US style='font-size:16.0pt;font-family:�з���'>
    <o:p></o:p></span></p>
  </td>
  <td width=345 colspan=5 valign=top style='width:258.75pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.85pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>�ժ���<span
  lang=EN-US><o:p></o:p></span></span></p>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>���v<span
  class=GramE>�Nñ�H</span><span lang=EN-US>(</span>�|��<span lang=EN-US>)<o:p></o:p></span></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:10;mso-yfti-lastrow:yes;height:17.7pt'>
  <td width=981 colspan=15 valign=top style='width:735.45pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.7pt'>
  <p class=MsoListParagraph style='margin-left:0cm;mso-para-margin-left:0gd;
  line-height:14.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span
  style='mso-bidi-font-size:12.0pt;font-family:�з���'>���иԾ\�H�U���`�N�ƶ��G</span></p>
  <p class=MsoListParagraph style='margin-left:18.0pt;mso-para-margin-left:0gd;text-indent:-18.0pt;line-height:14.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>&nbsp;�@�B�����W�w�A�гw�ܤH�ƫǺ����u�k�O�W���v�d�\�C</span></p>
  <p class=MsoListParagraph style='margin-left:18.0pt; mso-para-margin-left:0gd; text-indent:-18.0pt; line-height:12pt;layout-grid-mode:char; mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>&nbsp;�G�B�̡u��F�|��a��ǩe���|�ɧU�M�D��s�p�e�U�z�H�����Ϊ`�N�ƶ��v�W�w���H�A�i�ΧU�z�H���ɡA���ѭp�e�D���H�ƥ��֭��A�l�o���Τ��C</span></p>
  <p class=MsoListParagraph style='margin-left:18.0pt;mso-para-margin-left:0gd;text-indent:-18.0pt;line-height:14.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>&nbsp;�T�B�̡u��F�|�Ω��ݦU�����Ǯ��{�ɤH���i�ΤιB�έn�I�v�ά�޳��W�w���H�A���j�׶i�ξ��������B�p�e�D���H�B�@�P�D���H�Ω��ݳ��D�ޤ��t���ΤT�˵��H����ˡB�ÿˬ��U�z�H���A�p���H�ϳW�w�A�����־P�����g�O�C</span></p>
  
  <p class=MsoListParagraph style='margin-left:18.0pt;mso-para-margin-left:0gd;text-indent:-18.0pt;line-height:14.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>&nbsp;�|�B���ձM���H������ݥ��H�����{�ɤu�ɡA���g��¾�p�e�D���H�]�γ��D�ޡ^�P�N�A�иӭp�e�D���H�]�γ��D�ޡ^��ӭ��Ƶ���ñ���A�H��P�N�C</span></p>
  
  <p class=MsoListParagraph style='margin-left:17.85pt;text-indent:-17.85pt;line-height:14.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>&nbsp;���B�դh�Կ�H���˪�����ҩ����C</span></p>
  
  <p class=MsoListParagraph style='margin-left:17.85pt;mso-para-margin-left:0gd;text-indent:-17.85pt;line-height:14.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>&nbsp;���B�~�y�H�h���˪��u�@�\�i�ҡA���δ������b�u�@�\�i���������F�̴N�~�A�Ȫk�Ĥ��Q���W�w�~�y�ǥͤu�@�ɶ����H�����~�A�C�P���̪���16�p�ɡC</span></p>
  
  <p class=MsoListParagraph style='margin-left:17.85pt;mso-para-margin-left:0gd;text-indent:-17.85pt;line-height:14.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>&nbsp;�C�B�ե~�H�h�Ш̡u�ե~�H�h�ӽЭݥ��H�����{�ɤu�ҥ�v���H�K��v�˪����������ҩ����C</span></p>
  
  <p class=MsoListParagraph style='margin-left:17.85pt;mso-para-margin-left:0gd;text-indent:-17.85pt;line-height:14.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>&nbsp;�K�B�ݥ��H�����{�ɤu�Юֳ�Х��|��D�p�ǡA�A�|��H�ƫǡC</span></p>
  
  <p class=MsoListParagraph style='margin-left:17.85pt;mso-para-margin-left:0gd;text-indent:-17.85pt;line-height:14.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:�з���'>&nbsp;�E�B��������u�@�O�ɡA���˪��ݥ��H�����{�ɤu�Юֳ�v���C</span></p>
  </td>
 </tr>
 <![if !supportMisalignedColumns]>
 <tr height=0>
  <td width=79 style='border:none'></td>
  <td width=67 style='border:none'></td>
  <td width=101 style='border:none'></td>
  <td width=72 style='border:none'></td>
  <td width=42 style='border:none'></td>
  <td width=38 style='border:none'></td>
  <td width=80 style='border:none'></td>
  <td width=78 style='border:none'></td>
  <td width=79 style='border:none'></td>
  <td width=85 style='border:none'></td>
  <td width=146 style='border:none'></td>
  <td width=113 style='border:none'></td>
 </tr>
 <![endif]>
</table>

<p class=MsoNormal><span lang=EN-US><o:p>&nbsp;</o:p></span></p>

</div>

</body>

</html>
<%Response.ContentType = "application/vnd.ms-word"%> 
<%Response.AddHeader "content-disposition","attachment; filename=form1_"+cstr(date())+ ".doc"%>