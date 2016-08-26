<?php
namespace App\Libs;

class Whois
{
    /**
     * 获取 whois 信息
     * @param string $url       查询的域名
     * @param bool $more        是否返回全量信息
     * @param bool $formart     如果返回全量,是否格式化输出
     * @return Whois|array|string
     */
    public static function get($url='', $more=false, $formart=true)
    {
        $whois = new self();
        // 获取一级域名
        $domain = $whois->getDomain($url);
        if(!$domain) {
            die("url error, please try again !");
        }

        $whois = $whois->LookupDomain($domain);
        if (!$whois) return false;
        
        if ($more == true) {
            if ($formart == false)
                return $whois;

            return static::baseInfo($whois, true);
        }
        return static::baseInfo($whois);
    }

    /**
     * 获取常用的信息
     * @param $whois        详细信息
     * @param bool $formart 是否格式化后全量返回
     * @return array
     */
    public static function baseInfo($whois, $formart=false)
    {
        $arr = explode("\n", $whois);

        $re = [];
        foreach ($arr as $k=>$v) {
            if (!empty($v)) {
                $newArr = explode(':', $v);
                if (!empty($newArr[1])) {
                    $key = str_replace(' ', '_', $newArr[0]);
                    $re[$key] = $newArr[1];
                }
            }
        }

        if ($formart) return $re;

        $doman = [];
        $doman['Registrant_Email'] = "null";
        // 注册人电话
        $doman['Registrant_Phone'] = "null";
        // 注册人
        $doman['Registrant_Name'] = "null";
        // 注册机构
        $doman['Registrant_Organization'] = "null";
        // 注册商
        $doman['Registrar_WHOIS_Server'] = "null";
        // qq
        $doman['qq'] = "null";

        // 如果开启了隐私保护, 只展示基本信息
        if (!start_with(trim($re['Admin_Name']), 'YinSi')) {
            // 注册人电话
            $doman['Registrant_Phone'] = $re['Registrant_Phone'];
            // 注册人
            $doman['Registrant_Name'] = $re['Registrant_Name'];
            // 注册机构
            $doman['Registrant_Organization'] = $re['Registrant_Organization'];
            // 注册商
            $doman['Registrar_WHOIS_Server'] = $re['Registrar_WHOIS_Server'];
            // 注册人邮箱
            $doman['Registrant_Email'] = $re['Registrant_Email'];

            if (strpos($doman['Registrant_Email'], '@qq.com')) {
                $doman['qq'] = explode('@', $doman['Registrant_Email'])[0];
            }
        }

        // 域名
        $doman['Domain_Name'] = $re['Domain_Name'];
        // 注册地址
        $doman['address'] = $re['Registrant_State/Province']." ".$re['Registrant_City'];
        // 注册时间
        $doman['Creation_Date'] = substr(trim($re['Creation_Date']), 0, 10);
        // 到期时间
        $doman['Registrar_Registration_Expiration_Date'] = substr(trim($re['Registrar_Registration_Expiration_Date']), 0, 10);

        return $doman;
    }

    /**
     * 获取 url 的一级域名, (特殊情况如 net.cn, com.cn, org.cn等, 不可获取)
     * @param $url
     */
    public function getDomain($url)
    {
        preg_match('/[\w][\w-]*\.(?:com\.cn|com|cn|co|net|org|gov|cc|biz|info)(\/|$)/isU', $url, $domain);
        return rtrim($domain[0], '/');
    }

    public function LookupDomain($domain){
        $whoisserver = "";

        $dotpos=strpos($domain,".");
        $domtld=substr($domain,$dotpos);

        if (isset(static::$whoisservers[$domtld]))
            $whoisserver = static::$whoisservers[$domtld];

        if(!$whoisserver) {
            return "Error: No appropriate Whois server found for <b>$domain</b> domain!";
        }
        $result = static::QueryWhoisServer($whoisserver, $domain);
        if(!$result) {
            return "Error: No results retrieved $domain !";
        }
        
        preg_match("/Whois Server: (.*)/", $result, $matches);
        if (!$matches) return false;

        $secondary = $matches[1];
        
        if($secondary) {
            $result = static::QueryWhoisServer($secondary, $domain);
        }
        return  $result;
    }

    public static function QueryWhoisServer($whoisserver, $domain) {
        $port = 43;
        $timeout = 10;
        $fp = @fsockopen($whoisserver, $port, $errno, $errstr, $timeout) or die("Socket Error " . $errno . " - " . $errstr);
        fputs($fp, $domain . "\r\n");
        $out = "";
        while(!feof($fp)){
            $out .= fgets($fp);
        }
        fclose($fp);
        return $out;
    }


    protected static $whoisservers = array(
        ".abogado" > "whois-dub.mm-registry.com",
        ".ac" => "whois.nic.ac",
        ".academy" => "whois.donuts.co",
        ".accountants" => "whois.donuts.co",
        ".active" => "whois.afilias-srs.net",
        ".actor" => "whois.unitedtld.com",
        ".ae" => "whois.aeda.net.ae",
        ".aero" => "whois.aero",
        ".af" => "whois.nic.af",
        ".ag" => "whois.nic.ag",
        ".agency" => "whois.donuts.co",
        ".ai" => "whois.ai",
        ".airforce" => "whois.unitedtld.com",
        ".allfinanz" => "whois.ksregistry.net",
        ".alsace" => "whois-alsace.nic.fr",
        ".am" => "whois.amnic.net",
        ".archi" => "whois.ksregistry.net",
        ".army" => "whois.rightside.co",
        ".arpa" => "whois.iana.org",
        ".as" => "whois.nic.as",
        ".asia" => "whois.nic.asia",
        ".associates" => "whois.donuts.co",
        ".at" => "whois.nic.at",
        ".attorney" => "whois.rightside.co",
        ".au" => "whois.audns.net.au",
        ".auction" => "whois.unitedtld.com",
        ".audio" => "whois.uniregistry.net",
        ".autos" => "whois.afilias-srs.net",
        ".aw" => "whois.nic.aw",
        ".ax" => "whois.ax",
        ".band" => "whois.rightside.co",
        ".bar" => "whois.nic.bar",
        ".bargains" => "whois.donuts.co",
        ".bayern" => "whois-dub.mm-registry.com",
        ".be" => "whois.dns.be",
        ".beer" => "whois-dub.mm-registry.com",
        ".berlin" => "whois.nic.berlin",
        ".best" => "whois.nic.best",
        ".bg" => "whois.register.bg",
        ".bi" => "whois1.nic.bi",
        ".bike" => "whois.donuts.co",
        ".bio" => "whois.ksregistry.net",
        ".biz" => "whois.biz",
        ".bj" => "whois.nic.bj",
        ".black" => "whois.afilias.net",
        ".blackfriday" => "whois.uniregistry.net",
        ".blue" => "whois.afilias.net",
        ".bmw" => "whois.ksregistry.net",
        ".bn" => "whois.bn",
        ".bnpparibas" => "whois.afilias-srs.net",
        ".bo" => "whois.nic.bo",
        ".boo" => "domain-registry-whois.l.google.com",
        ".boutique" => "whois.donuts.co",
        ".br" => "whois.registro.br",
        ".brussels" => "whois.nic.brussels",
        ".budapest" => "whois-dub.mm-registry.com",
        ".build" => "whois.nic.build",
        ".builders" => "whois.donuts.co",
        ".business" => "whois.donuts.co",
        ".bw" => "whois.nic.net.bw",
        ".by" => "whois.cctld.by",
        ".bzh" => "whois-bzh.nic.fr",
        ".ca" => "whois.cira.ca",
        ".cab" => "whois.donuts.co",
        ".cal" => "domain-registry-whois.l.google.com",
        ".camera" => "whois.donuts.co",
        ".camp" => "whois.donuts.co",
        ".cancerresearch" => "whois.nic.cancerresearch",
        ".capetown" => "capetown-whois.registry.net.za",
        ".capital" => "whois.donuts.co",
        ".cards" => "whois.donuts.co",
        ".care" => "whois.donuts.co",
        ".career" => "whois.nic.career",
        ".careers" => "whois.donuts.co",
        ".casa" => "whois-dub.mm-registry.com",
        ".cash" => "whois.donuts.co",
        ".cat" => "whois.cat",
        ".catering" => "whois.donuts.co",
        ".cc" => "ccwhois.verisign-grs.com",
        ".center" => "whois.donuts.co",
        ".ceo" => "whois.nic.ceo",
        ".cern" => "whois.afilias-srs.net",
        ".cf" => "whois.dot.cf",
        ".ch" => "whois.nic.ch",
        ".channel" => "domain-registry-whois.l.google.com",
        ".cheap" => "whois.donuts.co",
        ".christmas" => "whois.uniregistry.net",
        ".chrome" => "domain-registry-whois.l.google.com",
        ".church" => "whois.donuts.co",
        ".ci" => "whois.nic.ci",
        ".city" => "whois.donuts.co",
        ".cl" => "whois.nic.cl",
        ".claims" => "whois.donuts.co",
        ".cleaning" => "whois.donuts.co",
        ".click" => "whois.uniregistry.net",
        ".clinic" => "whois.donuts.co",
        ".clothing" => "whois.donuts.co",
        ".club" => "whois.nic.club",
//        ".cn" => "whois.cnnic.cn",
//        "cn" =>"ewhois.cnnic.cn",
        ".co" => "whois.nic.co",
        ".codes" => "whois.donuts.co",
        ".coffee" => "whois.donuts.co",
        ".college" => "whois.centralnic.com",
        ".cologne" => "whois-fe1.pdt.cologne.tango.knipp.de",
        ".com" => "whois.verisign-grs.com",
        ".community" => "whois.donuts.co",
        ".company" => "whois.donuts.co",
        ".computer" => "whois.donuts.co",
        ".condos" => "whois.donuts.co",
        ".construction" => "whois.donuts.co",
        ".consulting" => "whois.unitedtld.com",
        ".contractors" => "whois.donuts.co",
        ".cooking" => "whois-dub.mm-registry.com",
        ".cool" => "whois.donuts.co",
        ".coop" => "whois.nic.coop",
        ".country" => "whois-dub.mm-registry.com",
        ".credit" => "whois.donuts.co",
        ".creditcard" => "whois.donuts.co",
        ".cruises" => "whois.donuts.co",
        ".cuisinella" => "whois.nic.cuisinella",
        ".cx" => "whois.nic.cx",
        ".cymru" => "whois.nic.cymru",
        ".cz" => "whois.nic.cz",
        ".dad" => "domain-registry-whois.l.google.com",
        ".dance" => "whois.unitedtld.com",
        ".dating" => "whois.donuts.co",
        ".day" => "domain-registry-whois.l.google.com",
        ".de" => "whois.denic.de",
        ".deals" => "whois.donuts.co",
        ".degree" => "whois.rightside.co",
        ".democrat" => "whois.unitedtld.com",
        ".dental" => "whois.donuts.co",
        ".dentist" => "whois.rightside.co",
        ".desi" => "whois.ksregistry.net",
        ".diamonds" => "whois.donuts.co",
        ".diet" => "whois.uniregistry.net",
        ".digital" => "whois.donuts.co",
        ".direct" => "whois.donuts.co",
        ".directory" => "whois.donuts.co",
        ".discount" => "whois.donuts.co",
        ".dk" => "whois.dk-hostmaster.dk",
        ".dm" => "whois.nic.dm",
        ".domains" => "whois.donuts.co",
        ".durban" => "durban-whois.registry.net.za",
        ".dvag" => "whois.ksregistry.net",
        ".dz" => "whois.nic.dz",
        ".eat" => "domain-registry-whois.l.google.com",
        ".ec" => "whois.nic.ec",
        ".edu" => "whois.educause.edu",
        ".education" => "whois.donuts.co",
        ".ee" => "whois.tld.ee",
        ".email" => "whois.donuts.co",
        ".emerck" => "whois.afilias-srs.net",
        ".engineer" => "whois.rightside.co",
        ".engineering" => "whois.donuts.co",
        ".enterprises" => "whois.donuts.co",
        ".equipment" => "whois.donuts.co",
        ".es" => "whois.nic.es",
        ".esq" => "domain-registry-whois.l.google.com",
        ".estate" => "whois.donuts.co",
        ".eu" => "whois.eu",
        ".eus" => "whois.eus.coreregistry.net",
        ".events" => "whois.donuts.co",
        ".exchange" => "whois.donuts.co",
        ".expert" => "whois.donuts.co",
        ".exposed" => "whois.donuts.co",
        ".fail" => "whois.donuts.co",
        ".farm" => "whois.donuts.co",
        ".feedback" => "whois.centralnic.com",
        ".fi" => "whois.fi",
        ".finance" => "whois.donuts.co",
        ".financial" => "whois.donuts.co",
        ".fish" => "whois.donuts.co",
        ".fishing" => "whois-dub.mm-registry.com",
        ".fitness" => "whois.donuts.co",
        ".flights" => "whois.donuts.co",
        ".florist" => "whois.donuts.co",
        ".flsmidth" => "whois.ksregistry.net",
        ".fly" => "domain-registry-whois.l.google.com",
        ".fo" => "whois.nic.fo",
        ".foo" => "domain-registry-whois.l.google.com",
        ".forsale" => "whois.unitedtld.com",
        ".foundation" => "whois.donuts.co",
        ".fr" => "whois.nic.fr",
        ".frl" => "whois.nic.frl",
        ".frogans" => "whois-frogans.nic.fr",
        ".fund" => "whois.donuts.co",
        ".furniture" => "whois.donuts.co",
        ".futbol" => "whois.unitedtld.com",
        ".gal" => "whois.gal.coreregistry.net",
        ".gallery" => "whois.donuts.co",
        ".gbiz" => "domain-registry-whois.l.google.com",
        ".gd" => "whois.nic.gd",
        ".gent" => "whois.nic.gent",
        ".gg" => "whois.gg",
        ".gi" => "whois2.afilias-grs.net",
        ".gift" => "whois.uniregistry.net",
        ".gifts" => "whois.donuts.co",
        ".gives" => "whois.rightside.co",
        ".gl" => "whois.nic.gl",
        ".glass" => "whois.donuts.co",
        ".gle" => "domain-registry-whois.l.google.com",
        ".global" => "whois.afilias-srs.net",
        ".globo" => "whois.gtlds.nic.br",
        ".gmail" => "domain-registry-whois.l.google.com",
        ".gmx" => "whois-fe1.gmx.tango.knipp.de",
        ".google" => "domain-registry-whois.l.google.com",
        ".gop" => "whois-cl01.mm-registry.com",
        ".gov" => "whois.dotgov.gov",
        ".gq" => "whois.dominio.gq",
        ".graphics" => "whois.donuts.co",
        ".gratis" => "whois.donuts.co",
        ".green" => "whois.afilias.net",
        ".gripe" => "whois.donuts.co",
        ".gs" => "whois.nic.gs",
        ".guide" => "whois.donuts.co",
        ".guitars" => "whois.uniregistry.net",
        ".guru" => "whois.donuts.co",
        ".gy" => "whois.registry.gy",
        ".hamburg" => "whois.nic.hamburg",
        ".haus" => "whois.unitedtld.com",
        ".healthcare" => "whois.donuts.co",
        ".help" => "whois.uniregistry.net",
        ".here" => "domain-registry-whois.l.google.com",
        ".hiphop" => "whois.uniregistry.net",
        ".hiv" => "whois.afilias-srs.net",
        ".hk" => "whois.hkirc.hk",
        ".hn" => "whois.nic.hn",
        ".holdings" => "whois.donuts.co",
        ".holiday" => "whois.donuts.co",
        ".homes" => "whois.afilias-srs.net",
        ".horse" => "whois-dub.mm-registry.com",
        ".host" => "whois.nic.host",
        ".hosting" => "whois.uniregistry.net",
        ".house" => "whois.donuts.co",
        ".how" => "domain-registry-whois.l.google.com",
        ".hr" => "whois.dns.hr",
        ".ht" => "whois.nic.ht",
        ".hu" => "whois.nic.hu",
        ".ibm" => "whois.nic.ibm",
        ".id" => "whois.pandi.or.id",
        ".ie" => "whois.domainregistry.ie",
        ".il" => "whois.isoc.org.il",
        ".im" => "whois.nic.im",
        ".immo" => "whois.donuts.co",
        ".immobilien" => "whois.unitedtld.com",
        ".in" => "whois.inregistry.net",
        ".industries" => "whois.donuts.co",
        ".info" => "whois.afilias.net",
        ".ing" => "domain-registry-whois.l.google.com",
        ".ink" => "whois.centralnic.com",
        ".institute" => "whois.donuts.co",
        ".insure" => "whois.donuts.co",
        ".int" => "whois.iana.org",
        ".international" => "whois.donuts.co",
        ".investments" => "whois.donuts.co",
        ".io" => "whois.nic.io",
        ".iq" => "whois.cmc.iq",
        ".ir" => "whois.nic.ir",
        ".is" => "whois.isnic.is",
        ".it" => "whois.nic.it",
        ".je" => "whois.je",
        ".jobs" => "jobswhois.verisign-grs.com",
        ".joburg" => "joburg-whois.registry.net.za",
        ".jp" => "whois.jprs.jp",
        ".juegos" => "whois.uniregistry.net",
        ".kaufen" => "whois.unitedtld.com",
        ".ke" => "whois.kenic.or.ke",
        ".kg" => "whois.domain.kg",
        ".ki" => "whois.nic.ki",
        ".kim" => "whois.afilias.net",
        ".kitchen" => "whois.donuts.co",
        ".kiwi" => "whois.nic.kiwi",
        ".koeln" => "whois-fe1.pdt.koeln.tango.knipp.de",
        ".kr" => "whois.kr",
        ".krd" => "whois.aridnrs.net.au",
        ".kz" => "whois.nic.kz",
        ".la" => "whois.nic.la",
        ".lacaixa" => "whois.nic.lacaixa",
        ".land" => "whois.donuts.co",
        ".lawyer" => "whois.rightside.co",
        ".lease" => "whois.donuts.co",
        ".lgbt" => "whois.afilias.net",
        ".li" => "whois.nic.li",
        ".life" => "whois.donuts.co",
        ".lighting" => "whois.donuts.co",
        ".limited" => "whois.donuts.co",
        ".limo" => "whois.donuts.co",
        ".link" => "whois.uniregistry.net",
        ".loans" => "whois.donuts.co",
        ".london" => "whois-lon.mm-registry.com",
        ".lotto" => "whois.afilias.net",
        ".lt" => "whois.domreg.lt",
        ".ltda" => "whois.afilias-srs.net",
        ".lu" => "whois.dns.lu",
        ".luxe" => "whois-dub.mm-registry.com",
        ".luxury" => "whois.nic.luxury",
        ".lv" => "whois.nic.lv",
        ".ly" => "whois.nic.ly",
        ".ma" => "whois.iam.net.ma",
        ".maison" => "whois.donuts.co",
        ".management" => "whois.donuts.co",
        ".mango" => "whois.mango.coreregistry.net",
        ".market" => "whois.rightside.co",
        ".marketing" => "whois.donuts.co",
        ".md" => "whois.nic.md",
        ".me" => "whois.nic.me",
        ".media" => "whois.donuts.co",
        ".meet" => "whois.afilias.net",
        ".melbourne" => "whois.aridnrs.net.au",
        ".meme" => "domain-registry-whois.l.google.com",
        ".menu" => "whois.nic.menu",
        ".mg" => "whois.nic.mg",
        ".miami" => "whois-dub.mm-registry.com",
        ".mini" => "whois.ksregistry.net",
        ".mk" => "whois.marnet.mk",
        ".ml" => "whois.dot.ml",
        ".mn" => "whois.nic.mn",
        ".mo" => "whois.monic.mo",
        ".mobi" => "whois.dotmobiregistry.net",
        ".moda" => "whois.unitedtld.com",
        ".monash" => "whois.nic.monash",
        ".mortgage" => "whois.rightside.co",
        ".moscow" => "whois.nic.moscow",
        ".motorcycles" => "whois.afilias-srs.net",
        ".mov" => "domain-registry-whois.l.google.com",
        ".mp" => "whois.nic.mp",
        ".ms" => "whois.nic.ms",
        ".mu" => "whois.nic.mu",
        ".museum" => "whois.museum",
        ".mx" => "whois.mx",
        ".my" => "whois.mynic.my",
        ".mz" => "whois.nic.mz",
        ".na" => "whois.na-nic.com.na",
        ".name" => "whois.nic.name",
        ".navy" => "whois.rightside.co",
        ".nc" => "whois.nc",
        ".net" => "whois.verisign-grs.com",
        ".network" => "whois.donuts.co",
        ".new" => "domain-registry-whois.l.google.com",
        ".nexus" => "domain-registry-whois.l.google.com",
        ".nf" => "whois.nic.nf",
        ".ng" => "whois.nic.net.ng",
        ".ngo" => "whois.publicinterestregistry.net",
        ".ninja" => "whois.unitedtld.com",
        ".nl" => "whois.domain-registry.nl",
        ".no" => "whois.norid.no",
        ".nra" => "whois.afilias-srs.net",
        ".nrw" => "whois.nic.nrw",
        ".nu" => "whois.iis.nu",
        ".nz" => "whois.srs.net.nz",
        ".om" => "whois.registry.om",
        ".ong" => "whois.publicinterestregistry.net",
        ".onl" => "whois.afilias-srs.net",
        ".ooo" => "whois.nic.ooo",
        ".org" => "whois.pir.org",
        ".organic" => "whois.afilias.net",
        ".ovh" => "whois-ovh.nic.fr",
        ".paris" => "whois-paris.nic.fr",
        ".partners" => "whois.donuts.co",
        ".parts" => "whois.donuts.co",
        ".pe" => "kero.yachay.pe",
        ".pf" => "whois.registry.pf",
        ".photo" => "whois.uniregistry.net",
        ".photography" => "whois.donuts.co",
        ".photos" => "whois.donuts.co",
        ".physio" => "whois.nic.physio",
        ".pics" => "whois.uniregistry.net",
        ".pictures" => "whois.donuts.co",
        ".pink" => "whois.afilias.net",
        ".pizza" => "whois.donuts.co",
        ".pl" => "whois.dns.pl",
        ".place" => "whois.donuts.co",
        ".plumbing" => "whois.donuts.co",
        ".pm" => "whois.nic.pm",
        ".pohl" => "whois.ksregistry.net",
        ".poker" => "whois.afilias.net",
        ".post" => "whois.dotpostregistry.net",
        ".pr" => "whois.nic.pr",
        ".press" => "whois.nic.press",
        ".pro" => "whois.dotproregistry.net",
        ".prod" => "domain-registry-whois.l.google.com",
        ".productions" => "whois.donuts.co",
        ".prof" => "domain-registry-whois.l.google.com",
        ".properties" => "whois.donuts.co",
        ".property" => "whois.uniregistry.net",
        ".pt" => "whois.dns.pt",
        ".pub" => "whois.unitedtld.com",
        ".pw" => "whois.nic.pw",
        ".qa" => "whois.registry.qa",
        ".quebec" => "whois.quebec.rs.corenic.net",
        ".re" => "whois.nic.re",
        ".recipes" => "whois.donuts.co",
        ".red" => "whois.afilias.net",
        ".rehab" => "whois.rightside.co",
        ".reise" => "whois.nic.reise",
        ".reisen" => "whois.donuts.co",
        ".rentals" => "whois.donuts.co",
        ".repair" => "whois.donuts.co",
        ".report" => "whois.donuts.co",
        ".republican" => "whois.rightside.co",
        ".rest" => "whois.centralnic.com",
        ".restaurant" => "whois.donuts.co",
        ".reviews" => "whois.unitedtld.com",
        ".rich" => "whois.afilias-srs.net",
        ".rio" => "whois.gtlds.nic.br",
        ".rip" => "whois.rightside.co",
        ".ro" => "whois.rotld.ro",
        ".rocks" => "whois.unitedtld.com",
        ".rodeo" => "whois-dub.mm-registry.com",
        ".rs" => "whois.rnids.rs",
        ".rsvp" => "domain-registry-whois.l.google.com",
        ".ru" => "whois.tcinet.ru",
        ".ruhr" => "whois.nic.ruhr",
        ".sa" => "whois.nic.net.sa",
        ".saarland" => "whois.ksregistry.net",
        ".sarl" => "whois.donuts.co",
        ".sb" => "whois.nic.net.sb",
        ".sc" => "whois2.afilias-grs.net",
        ".sca" => "whois.nic.sca",
        ".scb" => "whois.nic.scb",
        ".schmidt" => "whois.nic.schmidt",
        ".schule" => "whois.donuts.co",
        ".scot" => "whois.scot.coreregistry.net",
        ".se" => "whois.iis.se",
        ".services" => "whois.donuts.co",
        ".sexy" => "whois.uniregistry.net",
        ".sg" => "whois.sgnic.sg",
        ".sh" => "whois.nic.sh",
        ".shiksha" => "whois.afilias.net",
        ".shoes" => "whois.donuts.co",
        ".si" => "whois.arnes.si",
        ".singles" => "whois.donuts.co",
        ".sk" => "whois.sk-nic.sk",
        ".sm" => "whois.nic.sm",
        ".sn" => "whois.nic.sn",
        ".so" => "whois.nic.so",
        ".social" => "whois.unitedtld.com",
        ".software" => "whois.rightside.co",
        ".solar" => "whois.donuts.co",
        ".solutions" => "whois.donuts.co",
        ".soy" => "domain-registry-whois.l.google.com",
        ".space" => "whois.nic.space",
        ".spiegel" => "whois.ksregistry.net",
        ".st" => "whois.nic.st",
        ".su" => "whois.tcinet.ru",
        ".supplies" => "whois.donuts.co",
        ".supply" => "whois.donuts.co",
        ".support" => "whois.donuts.co",
        ".surf" => "whois-dub.mm-registry.com",
        ".surgery" => "whois.donuts.co",
        ".sx" => "whois.sx",
        ".sy" => "whois.tld.sy",
        ".systems" => "whois.donuts.co",
        ".tatar" => "whois.nic.tatar",
        ".tattoo" => "whois.uniregistry.net",
        ".tax" => "whois.donuts.co",
        ".tc" => "whois.meridiantld.net",
        ".technology" => "whois.donuts.co",
        ".tel" => "whois.nic.tel",
        ".tf" => "whois.nic.tf",
        ".th" => "whois.thnic.co.th",
        ".tienda" => "whois.donuts.co",
        ".tips" => "whois.donuts.co",
        ".tirol" => "whois.nic.tirol",
        ".tk" => "whois.dot.tk",
        ".tl" => "whois.nic.tl",
        ".tm" => "whois.nic.tm",
        ".tn" => "whois.ati.tn",
        ".to" => "whois.tonic.to",
        ".today" => "whois.donuts.co",
        ".tools" => "whois.donuts.co",
        ".top" => "whois.nic.top",
        ".town" => "whois.donuts.co",
        ".toys" => "whois.donuts.co",
        ".tr" => "whois.nic.tr",
        ".training" => "whois.donuts.co",
        ".travel" => "whois.nic.travel",
        ".tui" => "whois.ksregistry.net",
        ".tv" => "tvwhois.verisign-grs.com",
        ".tw" => "whois.twnic.net.tw",
        ".tz" => "whois.tznic.or.tz",
        ".ua" => "whois.ua",
        ".ug" => "whois.co.ug",
        ".uk" => "whois.nic.uk",
        ".university" => "whois.donuts.co",
        ".uol" => "whois.gtlds.nic.br",
        ".us" => "whois.nic.us",
        ".uy" => "whois.nic.org.uy",
        ".uz" => "whois.cctld.uz",
        ".vacations" => "whois.donuts.co",
        ".vc" => "whois2.afilias-grs.net",
        ".ve" => "whois.nic.ve",
        ".vegas" => "whois.afilias-srs.net",
        ".ventures" => "whois.donuts.co",
        ".VERMÖGENSBERATER" => "whois.ksregistry.net",
        ".VERMÖGENSBERATUNG" => "whois.ksregistry.net",
        ".versicherung" => "whois.nic.versicherung",
        ".vet" => "whois.rightside.co",
        ".vg" => "ccwhois.ksregistry.net",
        ".viajes" => "whois.donuts.co",
        ".villas" => "whois.donuts.co",
        ".vision" => "whois.donuts.co",
        ".vlaanderen" => "whois.nic.vlaanderen",
        ".vodka" => "whois-dub.mm-registry.com",
        ".vote" => "whois.afilias.net",
        ".voting" => "whois.voting.tld-box.at",
        ".voto" => "whois.afilias.net",
        ".voyage" => "whois.donuts.co",
        ".vu" => "vunic.vu",
        ".wales" => "whois.nic.wales",
        ".wang" => "whois.gtld.knet.cn",
        ".watch" => "whois.donuts.co",
        ".website" => "whois.nic.website",
        ".wed" => "whois.nic.wed",
        ".wedding" => "whois-dub.mm-registry.com",
        ".wf" => "whois.nic.wf",
        ".wien" => "whois.nic.wien",
        ".wiki" => "whois.nic.wiki",
        ".wme" => "whois.centralnic.com",
        ".work" => "whois-dub.mm-registry.com",
        ".works" => "whois.donuts.co",
        ".world" => "whois.donuts.co",
        ".ws" => "whois.website.ws",
        ".wtc" => "whois.nic.wtc",
        ".wtf" => "whois.donuts.co",
        ".xxx" => "whois.nic.xxx",
        ".xyz" => "whois.nic.xyz",
        ".yachts" => "whois.afilias-srs.net",
        ".yoga" => "whois-dub.mm-registry.com",
        ".youtube" => "domain-registry-whois.l.google.com",
        ".yt" => "whois.nic.yt",
        ".zip" => "domain-registry-whois.l.google.com",
        ".zm" => "whois.nic.zm",
        ".zone" => "whois.donuts.co"
    );
}