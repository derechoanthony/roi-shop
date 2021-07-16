<?php


// Optionally define a folder which contains TTF fonts
// mPDF will look here before looking in the usual _MPDF_TTFONTPATH
// Useful if you already have a folder for your fonts
// e.g. on Windows: define("_MPDF_SYSTEM_TTFONTS", 'C:/Windows/Fonts/');
// Leave undefined if not required

// define("_MPDF_SYSTEM_TTFONTS", '');


// Optionally set font(s) (names as defined below in $this->fontdata) to use for missing characters
// when using useSubstitutions. Use a font with wide coverage - dejavusanscondensed is a good start
// only works using subsets (otherwise would add very large file)
// doesn't do Indic or arabic
// More than 1 font can be specified but each will add to the processing time of the script

$this->backupSubsFont = array('dejavusanscondensed');


// Optionally set a font (name as defined below in $this->fontdata) to use for CJK characters
// in Plane 2 Unicode (> U+20000) when using useSubstitutions. 
// Use a font like hannomb or sun-extb if available
// only works using subsets (otherwise would add very large file)
// Leave undefined or blank if not not required

// $this->backupSIPFont = 'sun-extb';


/*
This array defines translations from font-family in CSS or HTML
to the internal font-family name used in mPDF. 
Can include as many as want, regardless of which fonts are installed.
By default mPDF will take a CSS/HTML font-family and remove spaces
and change to lowercase e.g. "Arial Unicode MS" will be recognised as
"arialunicodems"
You only need to define additional translations.
You can also use it to define specific substitutions e.g.
'frutiger55roman' => 'arial'
Generic substitutions (i.e. to a sans-serif or serif font) are set 
by including the font-family in $this->sans_fonts below
To aid backwards compatability some are included:
*/
$this->fonttrans = array(
	'helvetica' => 'arial',
	'times' => 'timesnewroman',
	'courier' => 'couriernew',
	'trebuchet' => 'trebuchetms',
	'comic' => 'comicsansms',
	'franklin' => 'franklingothicbook',
	'albertus' => 'albertusmedium',
	'arialuni' => 'arialunicodems',
	'zn_hannom_a' => 'hannoma',
	'ocr-b' => 'ocrb',
	'ocr-b10bt' => 'ocrb',


);

/*
This array lists the file names of the TrueType .ttf or .otf font files
for each variant of the (internal mPDF) font-family name.
['R'] = Regular (Normal), others are Bold, Italic, and Bold-Italic
Each entry must contain an ['R'] entry, but others are optional.
Only the font (files) entered here will be available to use in mPDF.
Put preferred default first in order.
This will be used if a named font cannot be found in any of 
$this->sans_fonts, $this->serif_fonts or $this->mono_fonts

['indic'] = true; for special mPDF fonts containing Indic characters
['sip-ext'] = 'hannomb'; name a related font file containing SIP characters

If a .ttc TrueType collection file is referenced, the number of the font
within the collection is required. Fonts in the collection are numbered 
starting at 1, as they appear in the .ttc file e.g.
	"cambria" => array(
		'R' => "cambria.ttc",
		'B' => "cambriab.ttf",
		'I' => "cambriai.ttf",
		'BI' => "cambriaz.ttf",
		'TTCfontID' => array(
			'R' => 1,	
			),
		),
	"cambriamath" => array(
		'R' => "cambria.ttc",
		'TTCfontID' => array(
			'R' => 2,	
			),
		),
*/

$this->fontdata = array(
	"avenir" => array(
		'R' => "Avenir-Medium-09.ttf",
		'B' => "Avenir-Heavy-05.ttf",
		'I' => "Avenir-Oblique-11.ttf",
		'BI' => "Avenir-HeavyOblique-06.ttf",
		),
	"bebasneue" => array(
		'R' => "BebasNeue-Regular.ttf",
		'B' => "BebasNeue-Bold.ttf",
		),	
	"bebasneuelight" => array(
		'R' => "BebasNeue-Light.ttf",
		'B' => "BebasNeue-Book.ttf",
		),
	"bebasneuethin" => array(
		'R' => "BebasNeue-Thin.ttf",
		),
	"jennasuepro" => array(
		'R' => "JennaSuePro.ttf",
		),
	"jennasue" => array(
		'R' => "JennaSue.ttf",
		),
	"myriadpro" => array(
		'R' => "MyriadPro-Light.ttf",
		),
	"myriadprolt" => array(
		'R' => "MyriadPro-LightIt.ttf",
		),
	"myriadproreg" => array(
		'R' => "MyriadPro-Regular.ttf",
		),	
	"itc" => array(
		'R' => "ITC.ttf",
		'B' => "ITC.ttf",
		),
	"gotham-medium" => array(
		'R' => "Gotham-Medium.ttf",
		'B' => "Gotham-Medium.ttf",
		),
	"gotham-light" => array(
		'R' => "Gotham-Light.ttf",
		'B' => "Gotham-Light.ttf",
		),
	"gotham-book" => array(
		'R' => "Gotham-Book.ttf",
		'B' => "Gotham-Book.ttf",
		),
	"gotham-bold" => array(
		'R' => "Gotham-Bold.ttf",
		'B' => "Gotham-Bold.ttf",
		),
	"poppins" => array(
		'R' => "Poppins-Regular.ttf",
		'B' => "Poppins-Bold.ttf",
		),
	"poppinslight" => array(
		'R' => "Poppins-Light.ttf",
		'B' => "Poppins-SemiBold.ttf",
		),
	"centurygothic" => array(
		'R' => "CenturyGothic-Regular.ttf",
		'B' => "CenturyGothic-Bold.ttf",
		),
	"museo300" => array(
		'R' => "museo300-regular.ttf",
		),
	"dejavusanscondensed" => array(
		'R' => "DejaVuSansCondensed.ttf",
		'B' => "DejaVuSansCondensed-Bold.ttf",
		'I' => "DejaVuSansCondensed-Oblique.ttf",
		'BI' => "DejaVuSansCondensed-BoldOblique.ttf",
		),
	"dejavusans" => array(
		'R' => "DejaVuSans.ttf",
		'B' => "DejaVuSans-Bold.ttf",
		'I' => "DejaVuSans-Oblique.ttf",
		'BI' => "DejaVuSans-BoldOblique.ttf",
		),
	"dejavuserif" => array(
		'R' => "DejaVuSerif.ttf",
		'B' => "DejaVuSerif-Bold.ttf",
		'I' => "DejaVuSerif-Italic.ttf",
		'BI' => "DejaVuSerif-BoldItalic.ttf",
		),
	"dejavuserifcondensed" => array(
		'R' => "DejaVuSerifCondensed.ttf",
		'B' => "DejaVuSerifCondensed-Bold.ttf",
		'I' => "DejaVuSerifCondensed-Italic.ttf",
		'BI' => "DejaVuSerifCondensed-BoldItalic.ttf",
		),
	"dejavusansmono" => array(
		'R' => "DejaVuSansMono.ttf",
		'B' => "DejaVuSansMono-Bold.ttf",
		'I' => "DejaVuSansMono-Oblique.ttf",
		'BI' => "DejaVuSansMono-BoldOblique.ttf",
		),
	"avenirltstd" => array(
		'R' => "AvenirLTStd-Medium.ttf",
		),	
	"minionpro" => array(
		'R' => "MinionPro-Regular.ttf",
		),		
	"playfair" => array(
		'R' => "PlayfairDisplay-Regular.ttf",
		'B' => "PlayfairDisplay-Bold.ttf",
		'I' => "PlayfairDisplay-Italic.ttf",
		'BI' => "PlayfairDisplay-BoldItalic.ttf",
		),	
	"robotoslab" => array(
		'R' => "RobotoSlab-Regular.ttf",
		'B' => "RobotoSlab-Bold.ttf",
		),	
	"roboto" => array(
		'R' => "Roboto-Regular.ttf",
		'B' => "Roboto-Bold.ttf",
		'I' => "Roboto-Italic.ttf",
		'BI' => "Roboto-BoldItalic.ttf",
		),	
	"montserrat" => array(
		'R' => "Montserrat-Regular.ttf",
		'B' => "Montserrat-Bold.ttf",
		'I' => "Montserrat-Italic.ttf",
		'BI' => "Montserrat-BoldItalic.ttf",
		),	
		
	"MontserratBlack" => array(
		'R' => "Montserrat-Black.ttf",
		'I' => "Montserrat-BlackItalic.ttf",
		),	
	
	"MontserratThick" => array(
		'R' => "Montserrat-SemiBold.ttf",
		'B' => "Montserrat-ExtraBold.ttf",
		'I' => "Montserrat-SemiBoldItalic.ttf",
		'BI' => "Montserrat-ExtraBoldItalic.ttf",
		),
	
	"MontserratThin" => array(
		'R' => "Montserrat-Thin.ttf",
		'B' => "Montserrat-Regular.ttf",
		'I' => "Montserrat-ThinItalic.ttf",
		'BI' => "Montserrat-Italic.ttf",
		),	
		
	"MontserratLight" => array(
		'R' => "Montserrat-Light.ttf",
		'B' => "Montserrat-Regular.ttf",
		'I' => "Montserrat-LightItalic.ttf",
		'BI' => "Montserrat-Italic.ttf",
		),
	
	"MontserratLight" => array(
		'R' => "Montserrat-ExtraLight.ttf",
		'B' => "Montserrat-Light.ttf",
		'I' => "Montserrat-ExtraLightItalic.ttf",
		'BI' => "Montserrat-LightItalic.ttf",
		),	
		
	"FontAwesome" => array(
		'R' => "fontawesome-webfont.ttf",
		),

	"proxima" => array(
		'R' => 'proximanovat-thin-webfont.ttf',
	),


/* OCR-B font for Barcodes */
	"ocrb" => array(
		'R' => "ocrb10.ttf",
		),

/* Thai fonts */
	"garuda" => array(
		'R' => "Garuda.ttf",
		'B' => "Garuda-Bold.ttf",
		'I' => "Garuda-Oblique.ttf",
		'BI' => "Garuda-BoldOblique.ttf",
		),
	"norasi" => array(
		'R' => "Norasi.ttf",
		'B' => "Norasi-Bold.ttf",
		'I' => "Norasi-Oblique.ttf",
		'BI' => "Norasi-BoldOblique.ttf",
		),


/* Indic fonts */
	"ind_bn_1_001" => array(
		'R' => "ind_bn_1_001.ttf",
		'indic' => true,
		),
	"ind_hi_1_001" => array(
		'R' => "ind_hi_1_001.ttf",
		'indic' => true,
		),
	"ind_ml_1_001" => array(
		'R' => "ind_ml_1_001.ttf",
		'indic' => true,
		),
	"ind_kn_1_001" => array(
		'R' => "ind_kn_1_001.ttf",
		'indic' => true,
		),
	"ind_gu_1_001" => array(
		'R' => "ind_gu_1_001.ttf",
		'indic' => true,
		),
	"ind_or_1_001" => array(
		'R' => "ind_or_1_001.ttf",
		'indic' => true,
		),
	"ind_ta_1_001" => array(
		'R' => "ind_ta_1_001.ttf",
		'indic' => true,
		),
	"ind_te_1_001" => array(
		'R' => "ind_te_1_001.ttf",
		'indic' => true,
		),
	"ind_pa_1_001" => array(
		'R' => "ind_pa_1_001.ttf",
		'indic' => true,
		),

/* Custom Fonts */

	"tradegothic" => array(
		'R' => "Trade Gothic LT.ttf",
		'B' => "Trade Gothic LT Bold.ttf"
		),



/* XW Zar Arabic fonts */
	"xbriyaz" => array(
		'R' => "XB Riyaz.ttf",
		'B' => "XB RiyazBd.ttf",
		'I' => "XB RiyazIt.ttf",
		'BI' => "XB RiyazBdIt.ttf",
		'unAGlyphs' => true,
		),
	"xbzar" => array(
		'R' => "XB Zar.ttf",
		'B' => "XB Zar Bd.ttf",
		'I' => "XB Zar It.ttf",
		'BI' => "XB Zar BdIt.ttf",
		'unAGlyphs' => true,
		),
	"opensans" => array(
		'R' => "OpenSans-Regular.ttf",
		'B' => "OpenSans-Bold.ttf",
		'I' => "OpenSans-Italic.ttf",
		'BI' => "OpenSans-BoldItalic.ttf"
		)




/* Examples of some CJK fonts */
/*
	"unbatang_0613" => array(
		'R' => "UnBatang_0613.ttf",
		),
	"sun-exta" => array(
		'R' => "Sun-ExtA.ttf",
		'sip-ext' => 'sun-extb',
		),
	"sun-extb" => array(
		'R' => "Sun-ExtB.ttf",
		),
	"hannoma" => array(
		'R' => "HAN NOM A.ttf",
		'sip-ext' => 'hannomb',	
		),
	"hannomb" => array(
		'R' => "HAN NOM B.ttf",
		),


	'mingliu' => array (
		'R' => 'mingliu.ttc',
		'TTCfontID' => array (
			'R' => 1,
		),
		'sip-ext' => 'mingliu-extb',
	),
	'pmingliu' => array (
		'R' => 'mingliu.ttc',
		'TTCfontID' => array (
			'R' => 2,
		),
		'sip-ext' => 'pmingliu-extb',
	),
	'mingliu_hkscs' => array (
		'R' => 'mingliu.ttc',
		'TTCfontID' => array (
			'R' => 3,
		),
		'sip-ext' => 'mingliu_hkscs-extb',
	),
	'mingliu-extb' => array (
		'R' => 'mingliub.ttc',
		'TTCfontID' => array (
			'R' => 1,
		),
	),
	'pmingliu-extb' => array (
		'R' => 'mingliub.ttc',
		'TTCfontID' => array (
			'R' => 2,
		),
	),
	'mingliu_hkscs-extb' => array (
		'R' => 'mingliub.ttc',
		'TTCfontID' => array (
			'R' => 3,
		),
	),
*/

);


// Add fonts to this array if they contain characters in the SIP or SMP Unicode planes
// but you do not require them. This allows a more efficient form of subsetting to be used.
$this->BMPonly = array(
	"dejavusanscondensed",
	"dejavusans",
	"dejavuserifcondensed",
	"dejavuserif",
	"dejavusansmono",
	);

// These next 3 arrays do two things:
// 1. If a font referred to in HTML/CSS is not available to mPDF, these arrays will determine whether
//    a serif/sans-serif or monospace font is substituted
// 2. The first font in each array will be the font which is substituted in circumstances as above
//     (Otherwise the order is irrelevant)
// Use the mPDF font-family names i.e. lowercase and no spaces (after any translations in $fonttrans)
// Always include "sans-serif", "serif" and "monospace" etc.
$this->sans_fonts = array('dejavusanscondensed','dejavusans','freesans','liberationsans','sans','sans-serif','cursive','fantasy', 
				'arial','helvetica','verdana','geneva','lucida','arialnarrow','arialblack','arialunicodems',
				'franklin','franklingothicbook','tahoma','garuda','calibri','trebuchet','lucidagrande','microsoftsansserif',
				'trebuchetms','lucidasansunicode','franklingothicmedium','albertusmedium','xbriyaz','albasuper','quillscript','opensans'

);

$this->serif_fonts = array('dejavuserifcondensed','dejavuserif','freeserif','liberationserif','serif',
				'timesnewroman','times','centuryschoolbookl','palatinolinotype','centurygothic',
				'bookmanoldstyle','bookantiqua','cyberbit','cambria',
				'norasi','charis','palatino','constantia','georgia','albertus','xbzar','algerian','garamond',
);

$this->mono_fonts = array('dejavusansmono','freemono','liberationmono','courier', 'mono','monospace','ocrb','ocr-b','lucidaconsole',
				'couriernew','monotypecorsiva'
);

?>