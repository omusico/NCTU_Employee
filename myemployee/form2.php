<?
	include("connectSQL.php");
	include("function.php");
		
	header("Content-type: application/vnd.ms-word");
	//header("Content-Disposition: attachment;Filename=".date("Ymd")."_form2.doc");
	header("Content-Disposition: attachment;Filename=".date("YmdHis")."_".filterEvil($_GET["OrderNo"])."_form2.doc");
		
?>

<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 14">
<meta name=Originator content="Microsoft Word 14">
<link rel=File-List href="form2.files/filelist.xml">
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>Emiya</o:Author>
  <o:LastAuthor>黃向偉</o:LastAuthor>
  <o:Revision>2</o:Revision>
  <o:TotalTime>51</o:TotalTime>
  <o:LastPrinted>2011-12-02T07:12:00Z</o:LastPrinted>
  <o:Created>2012-02-07T07:20:00Z</o:Created>
  <o:LastSaved>2012-02-07T07:20:00Z</o:LastSaved>
  <o:Pages>1</o:Pages>
  <o:Words>75</o:Words>
  <o:Characters>431</o:Characters>
  <o:Lines>3</o:Lines>
  <o:Paragraphs>1</o:Paragraphs>
  <o:CharactersWithSpaces>505</o:CharactersWithSpaces>
  <o:Version>14.00</o:Version>
 </o:DocumentProperties>
 <o:OfficeDocumentSettings>
  <o:AllowPNG/>
 </o:OfficeDocumentSettings>
</xml><![endif]-->
<link rel=dataStoreItem href="form2.files/item0001.xml"
target="form2.files/props002.xml">
<link rel=themeData href="form2.files/themedata.thmx">
<link rel=colorSchemeMapping href="form2.files/colorschememapping.xml">
<!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:SpellingState>Clean</w:SpellingState>
  <w:GrammarState>Clean</w:GrammarState>
  <w:TrackMoves>false</w:TrackMoves>
  <w:TrackFormatting/>
  <w:PunctuationKerning/>
  <w:DrawingGridHorizontalSpacing>6 點</w:DrawingGridHorizontalSpacing>
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
	{font-family:新細明體;
	panose-1:2 2 5 0 0 0 0 0 0 0;
	mso-font-alt:PMingLiU;
	mso-font-charset:136;
	mso-generic-font-family:roman;
	mso-font-pitch:variable;
	mso-font-signature:-1610611969 684719354 22 0 1048577 0;}
@font-face
	{font-family:新細明體;
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
	{font-family:標楷體;
	panose-1:3 0 5 9 0 0 0 0 0 0;
	mso-font-charset:136;
	mso-generic-font-family:script;
	mso-font-pitch:fixed;
	mso-font-signature:3 135135232 22 0 1048577 0;}
@font-face
	{font-family:"\@標楷體";
	panose-1:3 0 5 9 0 0 0 0 0 0;
	mso-font-charset:136;
	mso-generic-font-family:script;
	mso-font-pitch:fixed;
	mso-font-signature:3 135135232 22 0 1048577 0;}
@font-face
	{font-family:"\@新細明體";
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
	margin-bottom:0.0001pt;
	mso-pagination:none;
	font-size:10pt;
	mso-bidi-font-size:11.0pt;
	font-family:"Calibri","sans-serif";
	mso-fareast-font-family:新細明體;
	mso-bidi-font-family:"Times New Roman";
	mso-font-kerning:1.0pt;
}
p.MsoHeader, li.MsoHeader, div.MsoHeader
	{mso-style-priority:99;
	mso-style-link:"頁首 字元";
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:none;
	tab-stops:center 207.65pt right 415.3pt;
	layout-grid-mode:char;
	font-size:10.0pt;
	font-family:"Calibri","sans-serif";
	mso-fareast-font-family:新細明體;
	mso-bidi-font-family:"Times New Roman";
	mso-font-kerning:1.0pt;}
p.MsoFooter, li.MsoFooter, div.MsoFooter
	{mso-style-priority:99;
	mso-style-link:"頁尾 字元";
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:none;
	tab-stops:center 207.65pt right 415.3pt;
	layout-grid-mode:char;
	font-size:10.0pt;
	font-family:"Calibri","sans-serif";
	mso-fareast-font-family:新細明體;
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
	mso-fareast-font-family:新細明體;
	mso-bidi-font-family:"Times New Roman";
	mso-font-kerning:1.0pt;
}
span.a
	{mso-style-name:"頁首 字元";
	mso-style-priority:99;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-parent:"";
	mso-style-link:頁首;
	mso-ansi-font-size:10.0pt;
	mso-bidi-font-size:10.0pt;}
span.a0
	{mso-style-name:"頁尾 字元";
	mso-style-priority:99;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-parent:"";
	mso-style-link:頁尾;
	mso-ansi-font-size:10.0pt;
	mso-bidi-font-size:10.0pt;}
span.GramE
	{mso-style-name:"";
	mso-gram-e:yes;}
.MsoChpDefault
	{mso-style-type:export-only;
	mso-default-props:yes;
	mso-ascii-font-family:Calibri;
	mso-fareast-font-family:新細明體;
	mso-hansi-font-family:Calibri;}
 /* Page Definitions */
 @page
	{mso-page-border-surround-header:no;
	mso-page-border-surround-footer:no;
	/*mso-footnote-separator:url("form2.files/header.htm") fs;
	mso-footnote-continuation-separator:url("form2.files/header.htm") fcs;
	mso-endnote-separator:url("form2.files/header.htm") es;
	mso-endnote-continuation-separator:url("form2.files/header.htm") ecs;*/}
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
	mso-level-text:%2、;
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
	mso-level-text:%5、;
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
	mso-level-text:%8、;
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
div.MsoListParagraph1 {	mso-style-priority:34;
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
	mso-fareast-font-family:新細明體;
	mso-bidi-font-family:"Times New Roman";
	mso-font-kerning:1.0pt;
}
li.MsoListParagraph1 {	mso-style-priority:34;
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
	mso-fareast-font-family:新細明體;
	mso-bidi-font-family:"Times New Roman";
	mso-font-kerning:1.0pt;
}
p.MsoListParagraph1 {	mso-style-priority:34;
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
	mso-fareast-font-family:新細明體;
	mso-bidi-font-family:"Times New Roman";
	mso-font-kerning:1.0pt;
}
span.GramE1 {mso-style-name:"";
	mso-gram-e:yes;}
-->
</style>
<!--[if gte mso 10]>
<style>
 /* Style Definitions */
 table.MsoNormalTable
	{mso-style-name:表格內文;
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
	{mso-style-name:表格格線;
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
<?
	$OrderNo="";
	$OrderNo=filterEvil($_GET['OrderNo']);
	//$OrderNo="5";
	//查詢計畫資料
	$strSQL="select *,(datepart(year,CreateDate)-1911) as create_y,datepart(month,CreateDate) as create_m,".
			"datepart(day,CreateDate) as create_d,d.主管姓名 as depleader ".
			"from PT_Outline p ".
			"left join [PERSONDBOLD].[約用人員資料庫].[dbo].[DepartmentCode] d on d.code=p.leaderid ".
			"where SerialNo='".$OrderNo."'";
	$result=$db->query($strSQL);
	//echo $strSQL;
	$row=$result->fetch();
	
	$bugNo=trim($row['bugetno']);
	$create_y=trim($row['create_y']);
	$create_m=trim($row['create_m']);
	$create_d=trim($row['create_d']);
	$bugName=trim($row['bugname']);
	$leader=trim($row['leader']);
	$giveunit=trim($row['giveunit']);
	if(trim($row['depleader'])!=""){$leader=trim($row['depleader']);}//單位主管
	$DepName=trim($row['DepName']);
	$bug_start=trim($row['start']);
	if(strlen(trim($row['delay']))>0){
		$bug_end=trim($row['delay']);
	}else{
		$bug_end=trim($row['deadline']);
	}
?>
<p class=MsoNormal align=center style='text-align:center;line-height:16.0pt;
mso-line-height-rule:exactly'><span style='font-size:16.0pt;font-family:標楷體'><span
lang=EN-US><o:p></o:p></span></span></p>

<table border="0" width=981>
	<tr>
		<td colspan="2" align="center"><span style='font-size:16.0pt;font-family:標楷體'>國立交通大學計畫兼任人員及臨時工異動單</span></td>
	</tr>
	<tr>
		<td align="left"><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>
			填表日期：<?echo $create_y;?>年&nbsp;<?echo $create_m;?>月&nbsp;<?echo $create_d;?>日</span></td>
		<td align="right"><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>
			表單編號：<?echo $OrderNo;?></span></td>
	</tr>
</table>
<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 width=981
 style='width:735.45pt;border-collapse:collapse;border:none;mso-border-alt:
 solid windowtext .5pt;mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
 mso-border-insideh:.5pt solid windowtext;mso-border-insidev:.5pt solid windowtext'>
 <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;page-break-inside:avoid;
  height:28.65pt'>
  <td colspan=2 width=15% style='border:solid windowtext 1.0pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>計畫執行單位<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=25% colspan=4 style='border:solid windowtext 1.0pt;
  border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  lang=EN-US style='mso-bidi-font-size:12.0pt;font-family:標楷體'><o:p><?echo $DepName;?>&nbsp;</o:p></span></p>
  </td>
  <td colspan=2 width=15%  style='border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>計畫主持人<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td colspan=2 width=15%  style='border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  lang=EN-US style='mso-bidi-font-size:12.0pt;font-family:標楷體'><o:p><?echo $leader;?>&nbsp;</o:p></span></p>
  </td>
  <td colspan=1 width=15%  style='border:solid windowtext 1.0pt;
  border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>核定人事(業務)<br>金額<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td colspan=1 width=15%  valign=top style='border:solid windowtext 1.0pt;
  border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal><span lang=EN-US style='mso-bidi-font-size:12.0pt;
  font-family:標楷體'><o:p>&nbsp;</o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:1;page-break-inside:avoid;height:28.65pt'>
  <td colspan=2 style='border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>計畫執行期限<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td colspan=4 style='border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  lang=EN-US style='mso-bidi-font-size:12.0pt;font-family:標楷體'><o:p>
    <o:p><?echo $bug_start;?>&nbsp;-<?echo $bug_end;?>
    </o:p>
    &nbsp;</o:p></span></p>
  </td>
  <td colspan=2 style='border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>會計編號及計畫名稱<span lang=EN-US>
  <o:p></o:p></span></span></p>
  </td>
  <td colspan=4 valign=top style='border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal><span lang=EN-US style='mso-bidi-font-size:12.0pt;
  font-family:標楷體'><o:p><?echo $bugNo."-".$bugName;?>&nbsp;</o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:2;page-break-inside:avoid;height:28.65pt'>
  <td colspan=2 style='border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>計畫委託/補助單位</span></p>
  </td>
  <td colspan=4 style='border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  lang=EN-US style='mso-bidi-font-size:12.0pt;font-family:標楷體'><?echo $givenUnit;?></span></p>
  </td>
  <td colspan=6 style='border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:28.65pt'>
  <p class=MsoNormal style='line-height:16.0pt;mso-line-height-rule:exactly'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>
  <?
	$strSQL="select count(*) as num from PT_Employed where RecordStatus<>'-1' and SerialNo='".$OrderNo."' and BossRelation='1'";
	$result=$db->query($strSQL);
	$row=$result->fetch();
	$ifBossRelation=$row['num'];
	if($ifBossRelation>0){echo "█是    □否";}
	else{echo "□是    █否";}
  ?></span> <span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>(案內專任人員或臨時工是否為校長、計畫主持人、共同主持人及所屬單位主管之配偶及三親等內之血親、姻親)</span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:3;height:24.1pt'>
  <td colspan=2 style='border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:24.1pt'>
  <p class=MsoNormal align=center style='text-align:center;line-height:12.0pt;
  layout-grid-mode:char;mso-layout-grid-align:none'><span style='mso-bidi-font-size:
  12.0pt;font-family:標楷體'>員工代號及職稱</span><span style='font-size:10.0pt;font-family:
  標楷體'><span style="mso-bidi-font-size:12.0pt;font-family:標楷體"></span>（現職人員）<span lang=EN-US><o:p></o:p></span></span></p>
  <p class=MsoNormal align=center style='text-align:center;line-height:12.0pt;
  layout-grid-mode:char;mso-layout-grid-align:none'><span style='mso-bidi-font-size:
  12.0pt;font-family:標楷體'>學號</span><span style='font-size:10.0pt;font-family:
  標楷體'>（學生）</span><span lang=EN-US style='mso-bidi-font-size:12.0pt;font-family:
  標楷體'><o:p></o:p></span></p>
  <p class=MsoNormal align=center style='text-align:center;line-height:12.0pt;
  layout-grid-mode:char;mso-layout-grid-align:none'><span style='mso-bidi-font-size:
  12.0pt;font-family:標楷體'>身分證字號</span><span style='font-size:10.0pt;font-family:
  標楷體;letter-spacing:-1.0pt'>（校外人士）</span><span lang=EN-US style='mso-bidi-font-size:
  12.0pt;font-family:標楷體'><o:p></o:p></span></p>
  </td>
  <td width=7% style='border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:24.1pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>姓 名<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=8% style='border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:24.1pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>兼任職稱<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=10% colspan="2" style='border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:24.1pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:12.0pt;font-family:標楷體'>請核期間<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=10% style='border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:24.1pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>支領項目<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=15% colspan="2" style='border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:24.1pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>支領類別<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=5% style='border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:24.1pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>月支金額<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=15% style='border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:24.1pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:6.0pt;font-family:標楷體'>專任人員加會本職計畫主持人或單位主管同意<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td style='border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:24.1pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>備  註<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
 </tr>
 <p class=MsoNormal align=center style='text-align:center'><span
  style='mso-bidi-font-size:6.0pt;font-family:標楷體'>
  <?
	$print_str="";
	//抓出本次異動有資料的舊請核人員
	$org_eid=array();
	$str="select distinct Eid from [Mod_PrintInfo] where SerialNo='".$OrderNo."' and PrintType='0'";
	$result=$db->query($str) or die("Err:".$str);
	while($row=$result->fetch()){array_push($org_eid, trim($row['Eid']));}
	//print_r($org_eid);
	foreach($org_eid as $p){
		//抓原請核或異動資料
		$strSQL="select a.JobItem_1 as PTJobName,t.TitleName,p.*,".
				"(datepart(year,p.BeginDate)-1911) as start_y,datepart(month,p.BeginDate) as start_m,datepart(day,p.BeginDate) as start_d,".
				"(datepart(year,p.EndDate)-1911) as end_y,datepart(month,p.EndDate) as end_m,datepart(day,p.EndDate) as end_d ".
				"from [Mod_PrintInfo] p ".
				"left join title t on p.PTtitle=t.TitleCode ".
				"left join (select distinct SerialNo,JobItem_1 from [SALARYDB].[工作費資料庫].dbo.vw_PartTime_Rule v where v.parttime='1') as a on a.SerialNo=p.JobItemCode ".
				"where p.PrintType='0' and p.SerialNo='".$OrderNo."' and p.Eid='".$p."' ".
				"order by p.BeginDate";
		//echo $strSQL."<br>";
		$result=$db->query($strSQL) or die($strSQL);
		$row=$result->fetchAll();
		$org_record_num=count($row);
		$result=$db->query($strSQL) or die($strSQL);
		
		$row=$result->fetch();
		
		//echo print_r($row);
		$role=$row['Role'];
		$Title=$row['Title'];
		$idcode=$row['IdCode'];
		$name=$row['Name'];
		$TitleName=$row['TitleName'];
		$Eid=$row['Eid'];
		$comment=trim($row['Memo']);
		
		if(trim($row['TreaceBackReason'])!=""){$comment.="表單退回理由:<br>".$row['TreaceBackReason']."<br>";}
		
		$mod_before_Str1="<td width='30' rowspan='".$org_record_num."'>異<br>動<br>前</td><td>".$row['start_y']."/".$row['start_m']."/".
						 $row['start_d']."-".$row['end_y']."/".$row['end_m']."/".$row['end_d']."</td>";
		$mod_before_Str1.="<td>".trim($row['PTJobName'])."</td>";
		$mod_before_Str1.="<td colspan='2'>";
		if(trim($row['PayType'])=="hr_pay"){$mod_before_Str1.="時薪 ".trim($row['PayPerUnit'])." 元，每月上限 ".trim($row['LimitPerMonth'])." 小時</td>";}
		else if(trim($row['PayType'])=="case_pay"){$mod_before_Str1.="按件計酬 ".trim($row['PayPerUnit'])." 元/件，每月上限 ".trim($row['LimitPerMonth'])." 件</td>";}
		else if(trim($row['PayType'])=="day_pay"){$mod_before_Str1.="日薪 ".trim($row['PayPerUnit'])." 元/日，每月上限 ".trim($row['LimitPerMonth'])." 日</td>";}
		else if(trim($row['AwardLimit'])!=""){$mod_before_Str1.=trim($row['AwardLimit'])." 獎助單元</td>";}
		else if(trim($row['MonthExpense'])!=""){$mod_before_Str1.="月支 ".trim($row['MonthExpense'])." 元</td>";}
		
		$mod_before_Str1.="<td>".intval(trim($row['TotalAmount']))."</td>";
		$mod_before_Str2="";
		while($result && $row=$result->fetch()){
			//異動前資料
			$mod_before_Str2.="<tr><td>".$row['start_y']."/".$row['start_m']."/".$row['start_d']."-".
							$row['end_y']."/".$row['end_m']."/".$row['end_d']."</td>";
			$mod_before_Str2.="<td>".trim($row['PTJobName'])."</td>";
			$mod_before_Str2.="<td colspan='2'>";
			if(trim($row['PayType'])=="hr_pay"){$mod_before_Str2.="時薪 ".trim($row['PayPerUnit'])." 元，每月上限 ".trim($row['LimitPerMonth'])." 小時</td>";}
			else if(trim($row['PayType'])=="case_pay"){$mod_before_Str2.="按件計酬 ".trim($row['PayPerUnit'])." 元/件，每月上限 ".trim($row['LimitPerMonth'])." 件</td>";}
			else if(trim($row['PayType'])=="day_pay"){$mod_before_Str2.="日薪 ".trim($row['PayPerUnit'])." 元/日，每月上限 ".trim($row['LimitPerMonth'])." 日</td>";}
			else if(trim($row['AwardLimit'])!=""){$mod_before_Str2.=trim($row['AwardLimit'])." 獎助單元</td>";}
			else if(trim($row['MonthExpense'])!=""){$mod_before_Str2.="月支 ".trim($row['MonthExpense'])." 元</td>";}
			
			$mod_before_Str2.="<td>".intval(trim($row['TotalAmount']))."</td></tr>";
		}	
		//取得異動後資料
		$strSQL="select a.JobItem_1 as PTJobName,t.TitleName,p.*,d.DelayReason,".
				"(datepart(year,p.BeginDate)-1911) as start_y,datepart(month,p.BeginDate) as start_m,datepart(day,p.BeginDate) as start_d,".
				"(datepart(year,p.EndDate)-1911) as end_y,datepart(month,p.EndDate) as end_m,datepart(day,p.EndDate) as end_d ".
				"from [Mod_PrintInfo] p ".
				"left join title t on p.PTtitle=t.TitleCode ".
				"left join (select distinct SerialNo,JobItem_1 from [SALARYDB].[工作費資料庫].dbo.vw_PartTime_Rule v where v.parttime='1') as a on a.SerialNo=p.JobItemCode ".
				"left join DelayType d on d.DelayCode=p.DelayCode ".
				"where p.PrintType='1' and p.SerialNo='".$OrderNo."' and (p.FromEid='".$p."' or p.Eid='".$p."') ".
				"order by p.BeginDate";
		//echo $strSQL;
		$mod_result=$db->query($strSQL);
		$mod_row=$mod_result->fetchAll();
		$mod_record_num=count($mod_row);
		$mod_result=$db->query($strSQL);
		
		$mod_row=$mod_result->fetch();
		
		$mod_after_Str="<tr><td rowspan='".$mod_record_num."'>異<br>動<br>後</td>";
		if(trim($mod_row['Memo'])!=""){$comment.="<br>".$mod_row['Memo'];}
		//20150416加上延遲理由
		if(trim($mod_row['DelayReason'])!=""){
			$comment.="<br>延遲請核理由:<br>".trim($mod_row['DelayReason']);
		}
		$tempStr="";
		
		$tempStr.="<td>".$mod_row['start_y']."/".$mod_row['start_m']."/".$mod_row['start_d']."-".
				  $mod_row['end_y']."/".$mod_row['end_m']."/".$mod_row['end_d']."</td>";
		if(trim($mod_row['RecordStatus'])=='-2'){
			$tempStr.="<td colspan='4'><b><i>取消請核</i></b></td>";
		}else{
			$tempStr.="<td>".trim($mod_row['PTJobName'])."</td>";
			$tempStr.="<td colspan='2'>";
			if(trim($mod_row['PayType'])=="hr_pay"){
				if(trim($row['PayType'])!="hr_pay"){$tempStr.="<b><i>時薪</i></b> ";}
				else{$tempStr.="時薪 ";}
				
				if(trim($mod_row['PayPerUnit'])!=trim($row['PayPerUnit'])){
					$tempStr.="<b><i>".trim($mod_row['PayPerUnit'])."</i></b> 元/時";
				}else{
					$tempStr.=trim($mod_row['PayPerUnit'])." 元/時";
				}
				
				if(trim($mod_row['LimitPerMonth'])!=trim($row['LimitPerMonth'])){
					$tempStr.="，每月上限 <b><i>".trim($mod_row['LimitPerMonth'])."</i></b> 小時</td>";
				}else{
					$tempStr.="，每月上限 ".trim($mod_row['LimitPerMonth'])." 小時</td>";
				}
			}else if(trim($mod_row['PayType'])=="case_pay"){
				if(trim($row['PayType'])!="case_pay"){$tempStr.="<b><i>按件計酬</i></b> ";}
				else{$tempStr.="按件計酬 ";}
				
				if(trim($mod_row['PayPerUnit'])!=trim($row['PayPerUnit'])){
					$tempStr.="<b><i>".trim($mod_row['PayPerUnit'])."</i></b> 元/件";
				}else{
					$tempStr.=trim($mod_row['PayPerUnit'])." 元/件";
				}
				
				if(trim($mod_row['LimitPerMonth'])!=trim($row['LimitPerMonth'])){
					$tempStr.="，每月上限 <b><i>".trim($mod_row['LimitPerMonth'])."</i></b> 件</td>";
				}else{
					$tempStr.="，每月上限 ".trim($mod_row['LimitPerMonth'])." 件</td>";
				}
			}else if(trim($mod_row['PayType'])=="day_pay"){
				if(trim($row['PayType'])!="day_pay"){$tempStr.="<b><i>日薪</i></b> ";}
				else{$tempStr.="日薪 ";}
				
				if(trim($mod_row['PayPerUnit'])!=trim($row['PayPerUnit'])){
					$tempStr.="<b><i>".trim($mod_row['PayPerUnit'])."</i></b> 元/日";
				}else{
					$tempStr.=trim($mod_row['PayPerUnit'])." 元/日";
				}
				
				if(trim($mod_row['LimitPerMonth'])!=trim($row['LimitPerMonth'])){
					$tempStr.="，每月上限 <b><i>".trim($mod_row['LimitPerMonth'])."</i></b> 日</td>";
				}else{
					$tempStr.="，每月上限 ".trim($mod_row['LimitPerMonth'])." 日</td>";
				}
			}else if(trim($mod_row['AwardLimit'])!=""){
				if(trim($row['AwardLimit'])==""){
					$tempStr.=" <b><i>".trim($mod_row['AwardLimit'])." 獎助單元</i></b></td>";
				}else{
					$tempStr.=" ".trim($mod_row['AwardLimit'])." 獎助單元</td>";
				}				
			}else if(trim($mod_row['MonthExpense'])!=""){
				if(trim($row['MonthExpense'])==""){
					$tempStr.="<b><i>月支 ".trim($mod_row['MonthExpense'])." 元</i></b></td>";
				}else{
					$tempStr.="月支 ".trim($mod_row['MonthExpense'])." 元</td>";
				}
			}
			
			$tempStr.="<td>";
			if(trim($mod_row['TotalAmount'])!=trim($row['TotalAmount'])){
				$tempStr.="<b><i>".intval(trim($mod_row['TotalAmount']))."</i></b>";
			}else{
				$tempStr.=intval(trim($mod_row['TotalAmount']));
			}
			$tempStr.="</td>";
		}
		$mod_after_Str.=$tempStr."</tr>";
		
		while($mod_result && $mod_row=$mod_result->fetch()){
			if(trim($mod_row['Memo'])!=""){$comment.="<br>".$mod_row['Memo'];}
			//20150416加上延遲理由
			if(trim($mod_row['DelayReason'])!=""){
				$comment.="<br>追溯異動理由:<br>".trim($mod_row['DelayReason']);
			}
			$tempStr="";
			
			$tempStr.="<td>".$mod_row['start_y']."/".$mod_row['start_m']."/".$mod_row['start_d']."-".
					  $mod_row['end_y']."/".$mod_row['end_m']."/".$mod_row['end_d']."</td>";
			if(trim($mod_row['RecordStatus'])=='-2'){
				$tempStr.="<td colspan='4'><b><i>取消請核</i></b></td>";
			}else{
				$tempStr.="<td>".trim($mod_row['PTJobName'])."</td>";
				$tempStr.="<td colspan='2'>";
				if(trim($mod_row['PayType'])=="hr_pay"){
					if(trim($row['PayType'])!="hr_pay"){$tempStr.="<b><i>時薪</i></b> ";}
					else{$tempStr.="時薪 ";}
					
					if(trim($mod_row['PayPerUnit'])!=trim($row['PayPerUnit'])){
						$tempStr.="<b><i>".trim($mod_row['PayPerUnit'])."</i></b> 元/時";
					}else{
						$tempStr.=trim($mod_row['PayPerUnit'])." 元/時";
					}
					
					if(trim($mod_row['LimitPerMonth'])!=trim($row['LimitPerMonth'])){
						$tempStr.="，每月上限 <b><i>".trim($mod_row['LimitPerMonth'])."</i></b> 小時</td>";
					}else{
						$tempStr.="，每月上限 ".trim($mod_row['LimitPerMonth'])." 小時</td>";
					}
				}else if(trim($mod_row['PayType'])=="case_pay"){
					if(trim($row['PayType'])!="case_pay"){$tempStr.="<b><i>按件計酬</i></b> ";}
					else{$tempStr.="按件計酬 ";}
					
					if(trim($mod_row['PayPerUnit'])!=trim($row['PayPerUnit'])){
						$tempStr.="<b><i>".trim($mod_row['PayPerUnit'])."</i></b> 元/件";
					}else{
						$tempStr.=trim($mod_row['PayPerUnit'])." 元/件";
					}
					
					if(trim($mod_row['LimitPerMonth'])!=trim($row['LimitPerMonth'])){
						$tempStr.="，每月上限 <b><i>".trim($mod_row['LimitPerMonth'])."</i></b> 件</td>";
					}else{
						$tempStr.="，每月上限 ".trim($mod_row['LimitPerMonth'])." 件</td>";
					}
				}else if(trim($mod_row['PayType'])=="day_pay"){
					if(trim($row['PayType'])!="day_pay"){$tempStr.="<b><i>日薪</i></b> ";}
					else{$tempStr.="日薪 ";}
					
					if(trim($mod_row['PayPerUnit'])!=trim($row['PayPerUnit'])){
						$tempStr.="<b><i>".trim($mod_row['PayPerUnit'])."</i></b> 元/日";
					}else{
						$tempStr.=trim($mod_row['PayPerUnit'])." 元/日";
					}
					
					if(trim($mod_row['LimitPerMonth'])!=trim($row['LimitPerMonth'])){
						$tempStr.="，每月上限 <b><i>".trim($mod_row['LimitPerMonth'])."</i></b> 日</td>";
					}else{
						$tempStr.="，每月上限 ".trim($mod_row['LimitPerMonth'])." 日</td>";
					}
				}else if(trim($mod_row['AwardLimit'])!=""){
					if(trim($row['AwardLimit'])==""){
						$tempStr.=" <b><i>".trim($mod_row['AwardLimit'])." 獎助單元</i></b></td>";
					}else{
						$tempStr.=" ".trim($mod_row['AwardLimit'])." 獎助單元</td>";
					}				
				}else if(trim($mod_row['MonthExpense'])!=""){
					if(trim($row['MonthExpense'])==""){
						$tempStr.="<b><i>月支 ".trim($mod_row['MonthExpense'])." 元</i></b></td>";
					}else{
						$tempStr.="月支 ".trim($mod_row['MonthExpense'])." 元</td>";
					}
				}
				
				$tempStr.="<td>";
				if(trim($mod_row['TotalAmount'])!=trim($row['TotalAmount'])){
					$tempStr.="<b><i>".intval(trim($mod_row['TotalAmount']))."</i></b>";
				}else{
					$tempStr.=intval(trim($mod_row['TotalAmount']));
				}
			}
			$mod_after_Str.="<tr>".$tempStr."</tr>";
		}
			
		$print_str.= "<tr><td rowspan='".($mod_record_num+$org_record_num)."' colspan='2'>".$idcode."<br>";
		
		if($role=="E"){$print_str.=$Title;}
		else if($role=="S"){$print_str.=$stu_title[$Title];}
		else{
			$print_str.="校外人士 ";
			if($Title==""){$print_str.="無學歷資料";}
			else{
				if((int)$Title>3){$print_str.=$outer_title[$Title];}
				else{$print_str.=$stu_title[$Title];}
			}
		}
		
		$print_str.="</td>";
		$print_str.= "<td rowspan='".($mod_record_num+$org_record_num)."'>".$name."</td>".
					 "<td rowspan='".($mod_record_num+$org_record_num)."'>".$TitleName."</td>".$mod_before_Str1.
					 "<td rowspan='".($mod_record_num+$org_record_num)."'>&nbsp;</td>".
					 "<td rowspan='".($mod_record_num+$org_record_num)."'>".$comment."</td>".
					 "</tr>".$mod_before_Str2.$mod_after_Str;
		
	}
	echo $print_str;
 ?>
 </p>
 </table>
<script>
//console.log("<?echo $print_str?>");
</script>








<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 width=981
 style='width:735.45pt;border-collapse:collapse;border:none;mso-border-alt:
 solid windowtext .5pt;mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
 mso-border-insideh:.5pt solid windowtext;mso-border-insidev:.5pt solid windowtext'> 
 <tr style='mso-yfti-irow:13;mso-yfti-lastrow:yes;height:17.7pt'>
  <td width=981 colspan=16 valign=top style='width:735.45pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.7pt'>
  <p class=MsoListParagraph style='margin-left:18.0pt;mso-para-margin-left:0gd;text-indent:-18.0pt;line-height:14.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>※請詳閱以下應注意事項：</span></p>
  <p class=MsoListParagraph style='margin-left:18.0pt;mso-para-margin-left:0gd;text-indent:-18.0pt;line-height:14.0pt;layout-grid-mode:char;mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>&nbsp;一、相關規定，請逕至人事室網站「法令規章」查閱。</span></p>
  <p class=MsoListParagraph1 style='margin-left:18.0pt; mso-para-margin-left:0gd; text-indent:-18.0pt; line-height:12pt; layout-grid-mode:char; mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>&nbsp;二、依「本校兼任人員及臨時工管理要點」規定，兼任人員或臨時工應於起聘日前辦理兼任請核程序，經校內程序核准後，始得擔任之。</span></p>
  <p class=MsoListParagraph1 style='margin-left:18.0pt; mso-para-margin-left:0gd; text-indent:-18.0pt; line-height:12pt; layout-grid-mode:char; mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>&nbsp;三、依「本校兼任人員及臨時工管理要點」規定，應迴避進用校長、計畫主持人、共同主持人及所屬單位主管之配偶及三親等以內血親、姻親為助理人員，如有違反規定，不予核銷相關經費。</span></p>
  <p class=MsoListParagraph1 style='margin-left:18.0pt; mso-para-margin-left:0gd; text-indent:-18.0pt; line-height:12pt; layout-grid-mode:char; mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>&nbsp;四、本校專任人員已擔任兼任人員或臨時工，辦理異動時，仍應經本職單位主管（計畫專任人員加會計畫主持人，其他專任人員加會單位主管）核章以表同意。</span></p>
  <p class=MsoListParagraph1 style='margin-left:18.0pt; mso-para-margin-left:0gd; text-indent:-18.0pt; line-height:12pt; layout-grid-mode:char; mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>&nbsp;五、外籍人士請檢附工作許可證，僱用期間須在工作許可期限之內；依就業服務法第五十條規定外籍學生工作時間除寒暑假外，每星期最長為16小時。</span></p>
  <p class=MsoListParagraph1 style='margin-left:18.0pt; mso-para-margin-left:0gd; text-indent:-18.0pt; line-height:12pt; layout-grid-mode:char; mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>&nbsp;七、兼任人員及臨時工異動單請先會辦主計室，再會辦人事室。</span></p>
  <p class=MsoListParagraph1 style='margin-left:18.0pt; mso-para-margin-left:0gd; text-indent:-18.0pt; line-height:12pt; layout-grid-mode:char; mso-layout-grid-align:none'><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>&nbsp;九、首次報支工作費時，請檢附兼任人員及臨時工異動單影本。</span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:11;height:24.0pt'>
  <td width=79 colspan=2 style='width:59.3pt;border:solid windowtext 1.0pt;border-top:
  none;mso-border-top-alt:double windowtext 1.5pt;mso-border-alt:solid windowtext .5pt;
  mso-border-top-alt:double windowtext 1.5pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:24.0pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>業務單位<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=282 colspan=5 style='width:211.65pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:double windowtext 1.5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-top-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:24.0pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>承辦人<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=355 colspan=6 style='width:266.05pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:double windowtext 1.5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-top-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:24.0pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>計畫主持人</span><span
  lang=EN-US style='font-size:16.0pt;font-family:標楷體'><o:p></o:p></span></p>
  </td>
  <td width=265 colspan=3 valign=top style='width:7.0cm;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:double windowtext 1.5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;mso-border-top-alt:double windowtext 1.5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:24.0pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>單位主管<span
  lang=EN-US><o:p></o:p></span></span></p>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:標楷體;
  letter-spacing:-1.0pt'>（系所或中心主管）<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:12;height:34.85pt'>
  <td width=79 colspan=2 style='width:59.3pt;border:solid windowtext 1.0pt;border-top:
  none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:34.85pt'>
  <p class=MsoNormal style='text-align:justify;text-justify:inter-ideograph'><span
  style='mso-bidi-font-size:12.0pt;font-family:標楷體'>會辦及批示<span lang=EN-US><o:p></o:p></span></span></p>
  </td>
  <td width=282 colspan=5 style='width:211.65pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.85pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>主計室</span><span
  lang=EN-US style='font-size:16.0pt;font-family:標楷體'><o:p></o:p></span></p>
  </td>
  <td width=355 colspan=6 style='width:266.05pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.85pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>人事室</span><span
  lang=EN-US style='font-size:16.0pt;font-family:標楷體'><o:p></o:p></span></p>
  </td>
  <td width=265 colspan=3 valign=top style='width:7.0cm;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.85pt'>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>校長或<span
  lang=EN-US><o:p></o:p></span></span></p>
  <p class=MsoNormal><span style='mso-bidi-font-size:12.0pt;font-family:標楷體'>授權<span
  class=GramE>代簽人</span><span lang=EN-US>(</span>院長<span lang=EN-US>)<o:p></o:p></span></span></p>
  </td>
 </tr>
</table>

<p class=MsoNormal><span lang=EN-US><o:p>&nbsp;</o:p></span></p>

</div>

</body>

</html>