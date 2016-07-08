<?php

global $_W, $_GPC;
		$weid = $_W ['uniacid'];
		checklogin ();
		$operation = ! empty ( $_GPC ['op'] ) ? $_GPC ['op'] : 'display';
		if ($operation == 'display') {
			if (checksubmit ( 'submit2' )) {
				$sql = "
		        		INSERT INTO ".tablename('jy_ppp_xunithumb')." (`sex`, `mid`, `thumb`) VALUES
					
					(2,1,'images/jy_ppp/xuni/1/6b1cb522g90749125d63d&690.jpg'),(2,1,'images/jy_ppp/xuni/1/6b1cb522g9074913517b3&690.jpg'),(2,1,'images/jy_ppp/xuni/1/6b1cb522g907491445f23&690.jpg'),(2,1,'images/jy_ppp/xuni/1/6b1cb522g907491816cd5&690.jpg'),(2,10,'images/jy_ppp/xuni/10/14d7912397dda1441dcec6bcb4b7d0a20df486fd.png'),(2,10,'images/jy_ppp/xuni/10/7d380cd7912397dd0ebe320a5f82b2b7d1a287fd.png'),(2,10,'images/jy_ppp/xuni/10/892397dda144ad34e8782d89d6a20cf430ad85fd.png'),(2,10,'images/jy_ppp/xuni/10/8fdda144ad345982004d4f9c0af431adcaef84fd.png'),(2,100,'images/jy_ppp/xuni/100/1-1505030IG0-50.jpg'),(2,100,'images/jy_ppp/xuni/100/1-1505030IG0.jpg'),(2,100,'images/jy_ppp/xuni/100/1-1505030IG5.jpg'),(2,101,'images/jy_ppp/xuni/101/1-1505010S300.jpg'),(2,101,'images/jy_ppp/xuni/101/1-1505020P412.jpg'),(2,101,'images/jy_ppp/xuni/101/1-1505020P414.jpg'),(2,101,'images/jy_ppp/xuni/101/1-1505020P415.jpg'),(2,102,'images/jy_ppp/xuni/102/1-15042PQ049.jpg'),(2,102,'images/jy_ppp/xuni/102/1-15042PQ053-50.jpg'),(2,103,'images/jy_ppp/xuni/103/1-15042FI553-50.jpg'),(2,103,'images/jy_ppp/xuni/103/1-15042FI553.jpg'),(2,103,'images/jy_ppp/xuni/103/1-15042FI554.jpg'),(2,103,'images/jy_ppp/xuni/103/1-15042FI555-50.jpg'),(2,103,'images/jy_ppp/xuni/103/1-15042FI555.jpg'),(2,104,'images/jy_ppp/xuni/104/1-1504261kasdkaskd23R7.jpg'),(2,104,'images/jy_ppp/xuni/104/1-1504dsfsdf26123R5.jpg'),(2,105,'images/jy_ppp/xuni/105/1-1504240J539.jpg'),(2,105,'images/jy_ppp/xuni/105/1-1504240J541-50.jpg'),(2,105,'images/jy_ppp/xuni/105/1-1504240J541.jpg'),(2,106,'images/jy_ppp/xuni/106/1-1504230K635.jpg'),(2,106,'images/jy_ppp/xuni/106/1-1504230K637-50.jpg'),(2,106,'images/jy_ppp/xuni/106/1-1504230K638.jpg'),(2,107,'images/jy_ppp/xuni/107/1-1504220K210.jpg'),(2,107,'images/jy_ppp/xuni/107/1-1504220K211.jpg'),(2,107,'images/jy_ppp/xuni/107/1-1504220K212.jpg'),(2,107,'images/jy_ppp/xuni/107/1-1504220K213-50.jpg'),(2,108,'images/jy_ppp/xuni/108/1-1504210P117.jpg'),(2,108,'images/jy_ppp/xuni/108/1-1504210P119.jpg'),(2,108,'images/jy_ppp/xuni/108/1-1504210P120.jpg'),(2,109,'images/jy_ppp/xuni/109/1-1504200P214-50.jpg'),(2,109,'images/jy_ppp/xuni/109/1-1504200P214.jpg'),(2,109,'images/jy_ppp/xuni/109/1-1504200P217.jpg'),(2,109,'images/jy_ppp/xuni/109/1-1504200P219-50.jpg'),(2,109,'images/jy_ppp/xuni/109/1-1504200P220.jpg'),(2,11,'images/jy_ppp/xuni/11/7ad0f703918fa0ec2c92bf7b209759ee3d6ddb6b.jpg'),(2,11,'images/jy_ppp/xuni/11/ef03918fa0ec08fa7455c8d15fee3d6d55fbda6b.jpg'),(2,110,'images/jy_ppp/xuni/110/1-15041ZTasdasd445 (1).jpg'),(2,110,'images/jy_ppp/xuni/110/1-15041ZTtrtdg447-50 (2).jpg'),(2,110,'images/jy_ppp/xuni/110/1-15041dfgdfgZT447-50 (1).jpg'),(2,111,'images/jy_ppp/xuni/111/1-15041PU120-50.jpg'),(2,111,'images/jy_ppp/xuni/111/1-15041PU121.jpg'),(2,112,'images/jy_ppp/xuni/112/1-15041FR010.jpg'),(2,112,'images/jy_ppp/xuni/112/1-15041FR011.jpg'),(2,112,'images/jy_ppp/xuni/112/1-15041FR013.jpg'),(2,113,'images/jy_ppp/xuni/113/1-1504160R330.jpg'),(2,113,'images/jy_ppp/xuni/113/1-1504160R332-50.jpg'),(2,114,'images/jy_ppp/xuni/114/1-1504150SA1.jpg'),(2,114,'images/jy_ppp/xuni/114/1-1504150SA2-50.jpg'),(2,114,'images/jy_ppp/xuni/114/1-1504150SA2.jpg'),(2,114,'images/jy_ppp/xuni/114/1-1504150SA3.jpg'),(2,115,'images/jy_ppp/xuni/115/1-1504140UJ1.jpg'),(2,115,'images/jy_ppp/xuni/115/1-1504140UJ2.jpg'),(2,115,'images/jy_ppp/xuni/115/1-1504140UJ3-50.jpg'),(2,116,'images/jy_ppp/xuni/116/1-1504120T108-50.jpg'),(2,116,'images/jy_ppp/xuni/116/1-1504120T108.jpg'),(2,116,'images/jy_ppp/xuni/116/1-1504120T109.jpg'),(2,116,'images/jy_ppp/xuni/116/1-1504120T110-50.jpg'),(2,117,'images/jy_ppp/xuni/117/1-1504010S450.jpg'),(2,117,'images/jy_ppp/xuni/117/1-1504010S452.jpg'),(2,117,'images/jy_ppp/xuni/117/1-1504010S453.jpg'),(2,117,'images/jy_ppp/xuni/117/1-1504010S454.jpg'),(2,117,'images/jy_ppp/xuni/117/1-1504010S455.jpg'),(2,118,'images/jy_ppp/xuni/118/1-1503310TG6.jpg'),(2,118,'images/jy_ppp/xuni/118/1-1503310TG9-50.jpg'),(2,118,'images/jy_ppp/xuni/118/1-1503310TG9.jpg'),(2,119,'images/jy_ppp/xuni/119/1-150330150R7.jpg'),(2,119,'images/jy_ppp/xuni/119/1-150330150R9-50.jpg'),(2,119,'images/jy_ppp/xuni/119/1-150330150S0-50.jpg'),(2,119,'images/jy_ppp/xuni/119/1-150330150S0.jpg'),(2,12,'images/jy_ppp/xuni/12/284e251f95cad1c89b6ca8a77a3e6709c83d515e.jpg'),(2,12,'images/jy_ppp/xuni/12/3d1f95cad1c8a786f4fd1e8e6209c93d71cf505e.jpg'),(2,120,'images/jy_ppp/xuni/120/1-1503250R020.jpg'),(2,120,'images/jy_ppp/xuni/120/1-1503250R021.jpg'),(2,120,'images/jy_ppp/xuni/120/1-1503250R022.jpg'),(2,120,'images/jy_ppp/xuni/120/1-1503250R027.jpg'),(2,120,'images/jy_ppp/xuni/120/1-1503250R031.jpg'),(2,121,'images/jy_ppp/xuni/121/1-150323110236.jpg'),(2,121,'images/jy_ppp/xuni/121/1-150323110238.jpg'),(2,121,'images/jy_ppp/xuni/121/1-150323110240.jpg'),(2,121,'images/jy_ppp/xuni/121/1-150323110241.jpg'),(2,122,'images/jy_ppp/xuni/122/1-1503200Q529.jpg'),(2,122,'images/jy_ppp/xuni/122/1-1503200Q533.jpg'),(2,122,'images/jy_ppp/xuni/122/1-1503200Q538.jpg'),(2,123,'images/jy_ppp/xuni/123/1-15031ZR004.jpg'),(2,123,'images/jy_ppp/xuni/123/1-15031ZR005.jpg'),(2,123,'images/jy_ppp/xuni/123/1-15031ZR009.jpg'),(2,123,'images/jy_ppp/xuni/123/1-15031ZR014.jpg'),(2,124,'images/jy_ppp/xuni/124/1-15031PP543.jpg'),(2,124,'images/jy_ppp/xuni/124/1-15031PP548.jpg'),(2,124,'images/jy_ppp/xuni/124/1-15031PP551.jpg'),(2,125,'images/jy_ppp/xuni/125/1-150315120448.jpg'),(2,125,'images/jy_ppp/xuni/125/1-150315120449.jpg'),(2,125,'images/jy_ppp/xuni/125/1-150315120450.jpg'),(2,125,'images/jy_ppp/xuni/125/1-150315120455.jpg'),(2,126,'images/jy_ppp/xuni/126/1-150314101334.jpg'),(2,126,'images/jy_ppp/xuni/126/1-150314101335.jpg'),(2,126,'images/jy_ppp/xuni/126/1-150314101337.jpg'),(2,127,'images/jy_ppp/xuni/127/1-150313111K1.jpg'),(2,127,'images/jy_ppp/xuni/127/1-150313111K2.jpg'),(2,127,'images/jy_ppp/xuni/127/1-150313111K6.jpg'),(2,128,'images/jy_ppp/xuni/128/1-150312111059.jpg'),(2,128,'images/jy_ppp/xuni/128/1-150312111105.jpg'),(2,128,'images/jy_ppp/xuni/128/1-150312111106.jpg'),(2,129,'images/jy_ppp/xuni/129/1-150312111100.jpg'),(2,129,'images/jy_ppp/xuni/129/1-150312111102.jpg'),(2,129,'images/jy_ppp/xuni/129/1-150312111104.jpg'),(2,13,'images/jy_ppp/xuni/13/17b30f2442a7d9336c81f901ab4bd11373f00192.png'),(2,13,'images/jy_ppp/xuni/13/63899e510fb30f24e0b3f171ce95d143ad4b0392.png'),(2,13,'images/jy_ppp/xuni/13/86510fb30f2442a77a27e0d7d743ad4bd1130292.png'),(2,130,'images/jy_ppp/xuni/130/1-150311091115.jpg'),(2,130,'images/jy_ppp/xuni/130/1-150311091119.jpg'),(2,130,'images/jy_ppp/xuni/130/1-150311091125.jpg'),(2,131,'images/jy_ppp/xuni/131/1-1503100ZP1.jpg'),(2,131,'images/jy_ppp/xuni/131/1-1503100ZP3.jpg'),(2,131,'images/jy_ppp/xuni/131/1-1503100ZP5.jpg'),(2,131,'images/jy_ppp/xuni/131/1-1503100ZP7.jpg'),(2,131,'images/jy_ppp/xuni/131/1-1503100ZP8.jpg'),(2,14,'images/jy_ppp/xuni/14/127b02087bf40ad116656195512c11dfa8eccec7.jpg'),(2,14,'images/jy_ppp/xuni/14/1a087bf40ad162d9876fc46a17dfa9ec8b13cdc7.jpg'),(2,14,'images/jy_ppp/xuni/14/63f40ad162d9f2d321908299afec8a136227ccc7.jpg'),(2,15,'images/jy_ppp/xuni/15/ae003af33a87e950cc358e1f16385343fbf2b4a7.png'),(2,15,'images/jy_ppp/xuni/15/cd39b6003af33a87114f7d69c05c10385343b5a7.png'),(2,16,'images/jy_ppp/xuni/16/05950a7b02087bf4a67b389ef4d3572c11dfcf36.jpg'),(2,16,'images/jy_ppp/xuni/16/127b02087bf40ad1cf73a894512c11dfa9ecce36.jpg'),(2,17,'images/jy_ppp/xuni/17/2bfa828ba61ea8d3a3df7671910a304e241f58c1.png'),(2,17,'images/jy_ppp/xuni/17/9a8ba61ea8d3fd1f1ef4a04f364e251f94ca5fc1.png'),(2,17,'images/jy_ppp/xuni/17/a93533fa828ba61ef513ca5a4734970a314e59c1.png'),(2,18,'images/jy_ppp/xuni/18/0730e924b899a901043ed6391b950a7b0208f5b2.jpg'),(2,18,'images/jy_ppp/xuni/18/a099a9014c086e0657a3b24404087bf40ad1cbb2.jpg'),(2,18,'images/jy_ppp/xuni/18/b1014c086e061d95414dba377df40ad162d9cab2.jpg'),(2,18,'images/jy_ppp/xuni/18/f124b899a9014c082730a5aa0c7b02087bf4f4b2.jpg'),(2,19,'images/jy_ppp/xuni/19/21dbb6fd5266d0161bfe055c912bd40734fa35da.jpg'),(2,19,'images/jy_ppp/xuni/19/99cb39dbb6fd5266c1cca760ad18972bd50736da.jpg'),(2,19,'images/jy_ppp/xuni/19/aefd5266d0160924b8c2396fd20735fae7cd34da.jpg'),(2,2,'images/jy_ppp/xuni/2/asdeq2weqwzzx4354.jpg'),(2,2,'images/jy_ppp/xuni/2/fg564523423sdfsd.jpg'),(2,2,'images/jy_ppp/xuni/2/jxdsdas45fgdfgq.jpg'),(2,2,'images/jy_ppp/xuni/2/kkkosfsfrwerwerwerzxcz999.jpg'),(2,2,'images/jy_ppp/xuni/2/ksahdfakspqwe123asdzxf.jpg'),(2,2,'images/jy_ppp/xuni/2/sdfwe45r34534zx.jpg'),(2,20,'images/jy_ppp/xuni/20/0730e924b899a901f0002a411b950a7b0208f594.png'),(2,20,'images/jy_ppp/xuni/20/30381f30e924b8991209084f68061d950a7bf694.png'),(2,20,'images/jy_ppp/xuni/20/f124b899a9014c08d30e59d20c7b02087bf4f494.png'),(2,21,'images/jy_ppp/xuni/21/1246f21fbe096b6396c0a2260a338744eaf8acef.jpg'),(2,21,'images/jy_ppp/xuni/21/951001e93901213f2feab2aa52e736d12e2e95ef.jpg'),(2,21,'images/jy_ppp/xuni/21/aa119313b07eca80e490c591972397dda144832b.jpg'),(2,22,'images/jy_ppp/xuni/22/1124ab18972bd407f6abe78b7d899e510fb30953.jpg'),(2,22,'images/jy_ppp/xuni/22/c909b3de9c82d15846ee6dc7860a19d8bc3e4253.jpg'),(2,23,'images/jy_ppp/xuni/23/5e2309f7905298220b6ba4b6d1ca7bcb0a46d46f.jpg'),(2,23,'images/jy_ppp/xuni/23/673e6709c93d70cfc1ef3778fedcd100baa12baa.jpg'),(2,23,'images/jy_ppp/xuni/23/8022720e0cf3d7ca07aca203f41fbe096b63a969.jpg'),(2,23,'images/jy_ppp/xuni/23/93d4b31c8701a18bc8498f3a982f07082838fe21.jpg'),(2,23,'images/jy_ppp/xuni/23/a2a1cd11728b4710bb3860b9c5cec3fdfc0323ab.jpg'),(2,24,'images/jy_ppp/xuni/24/6b8da9773912b31bf2d5525c8018367adab4e10e.png'),(2,24,'images/jy_ppp/xuni/24/b1773912b31bb051bb9d2c5d307adab44aede00e.png'),(2,24,'images/jy_ppp/xuni/24/ee03738da9773912f29f1a14fe198618367ae20e.png'),(2,25,'images/jy_ppp/xuni/25/8a45d688d43f8794d352415fd41b0ef41bd53a15.jpg'),(2,25,'images/jy_ppp/xuni/25/cc3f8794a4c27d1eab8b32b51dd5ad6eddc43815.jpg'),(2,25,'images/jy_ppp/xuni/25/ce88d43f8794a4c2058eee5a08f41bd5ad6e3915.jpg'),(2,25,'images/jy_ppp/xuni/25/d0fcc3cec3fdfc03e1d5eac9d23f8794a4c22615.jpg'),(2,25,'images/jy_ppp/xuni/25/e4039245d688d43ff10498837b1ed21b0ef43b15.jpg'),(2,26,'images/jy_ppp/xuni/26/64d98d1001e93901476e414b7dec54e736d19653.png'),(2,26,'images/jy_ppp/xuni/26/951001e93901213fdf5f82a952e736d12f2e9553.png'),(2,27,'images/jy_ppp/xuni/27/2bfa828ba61ea8d3b2e30771910a304e241f5885.jpg'),(2,27,'images/jy_ppp/xuni/27/9a8ba61ea8d3fd1f11c8d14f364e251f94ca5f85.jpg'),(2,28,'images/jy_ppp/xuni/28/873df8dcd100baa1b5af5dcc4110b912c9fc2ec1.jpg'),(2,28,'images/jy_ppp/xuni/28/a2a1cd11728b4710c2ace7bbc5cec3fdfd0323c1.jpg'),(2,28,'images/jy_ppp/xuni/28/c900baa1cd11728b3dae9655cefcc3cec2fd2cc1.jpg'),(2,28,'images/jy_ppp/xuni/28/e0dcd100baa1cd110b356857bf12c8fcc2ce2dc1.jpg'),(2,29,'images/jy_ppp/xuni/29/2ed12f2eb9389b50a723aa208335e5dde6116ee0.jpg'),(2,29,'images/jy_ppp/xuni/29/4ce736d12f2eb9387cb13080d3628535e4dd6fe0.jpg'),(2,3,'images/jy_ppp/xuni/3/213sadfsfsdwerqwe.jpg'),(2,3,'images/jy_ppp/xuni/3/fhsdjfhsdfsdf213.jpg'),(2,3,'images/jy_ppp/xuni/3/q234fsdfwer23423q12.jpg'),(2,3,'images/jy_ppp/xuni/3/r43qwqawede43.jpg'),(2,30,'images/jy_ppp/xuni/30/1b4f78f0f736afc37dd5dd14b519ebc4b64512e1.jpg'),(2,30,'images/jy_ppp/xuni/30/b364034f78f0f736ac27ae700c55b319eac413e1.jpg'),(2,31,'images/jy_ppp/xuni/31/2112b31bb051f819bbec873cdcb44aed2f73e7ff.jpg'),(2,31,'images/jy_ppp/xuni/31/ab19ebc4b74543a9b2d27eba18178a82b80114fe.jpg'),(2,31,'images/jy_ppp/xuni/31/b7c379310a55b319c7e1050345a98226cffc17af.jpg'),(2,32,'images/jy_ppp/xuni/32/10fa513d269759eef22212bdb4fb43166d22df52.jpg'),(2,32,'images/jy_ppp/xuni/32/493d269759ee3d6d85b4f5bd45166d224f4ade52.jpg'),(2,32,'images/jy_ppp/xuni/32/b8ec08fa513d269797a17a2b53fbb2fb4316d852.jpg'),(2,33,'images/jy_ppp/xuni/33/5336acaf2edda3cc3d9d975707e93901213f924f.jpg'),(2,33,'images/jy_ppp/xuni/33/6e094b36acaf2edde388669e8b1001e93901934f.jpg'),(2,33,'images/jy_ppp/xuni/33/b4af2edda3cc7cd9cf541bae3f01213fb80e914f.jpg'),(2,33,'images/jy_ppp/xuni/33/d3ef76094b36acaf1199b98b7ad98d1001e99c4f.jpg'),(2,34,'images/jy_ppp/xuni/34/0dce36d3d539b60032e40cb6ef50352ac65cb78c.png'),(2,34,'images/jy_ppp/xuni/34/c133c895d143ad4b4bbb46c184025aafa40f061d.png'),(2,34,'images/jy_ppp/xuni/34/ea1fbe096b63f6249e8039028144ebf81b4ca3d6.png'),(2,35,'images/jy_ppp/xuni/35/393fb80e7bec54e7d774ad68bf389b504fc26a28.jpg'),(2,35,'images/jy_ppp/xuni/35/8a58d109b3de9c82d9fe4ef96a81800a19d84328.jpg'),(2,35,'images/jy_ppp/xuni/35/d7fc1e178a82b9012eac7445758da9773912ef47.jpg'),(2,36,'images/jy_ppp/xuni/36/22292df5e0fe99255775cd2632a85edf8db1714f.jpg'),(2,37,'images/jy_ppp/xuni/37/36dda3cc7cd98d10f72f5e47273fb80e7aec90c9.png'),(2,37,'images/jy_ppp/xuni/37/bbcc7cd98d1001e9cec74679be0e7bec55e797c9.png'),(2,38,'images/jy_ppp/xuni/38/0730e924b899a90157858b3e1b950a7b0208f502.png'),(2,38,'images/jy_ppp/xuni/38/f124b899a9014c08728bf8ad0c7b02087bf4f402.png'),(2,39,'images/jy_ppp/xuni/39/6ec6a7efce1b9d16585f62bff6deb48f8d546450.jpg'),(2,39,'images/jy_ppp/xuni/39/bfefce1b9d16fdfa09f43e30b18f8c5495ee7b50.jpg'),(2,39,'images/jy_ppp/xuni/39/fddde71190ef76c6054a03f59816fdfaae516750.jpg'),(2,39,'images/jy_ppp/xuni/39/ff1190ef76c6a7ef6dbe50f8f8faaf51f2de6650.jpg'),(2,4,'images/jy_ppp/xuni/4/3fe971cf3bc79f3d92abb0cfbaa1cd11708b29df.jpg'),(2,4,'images/jy_ppp/xuni/4/40295366d0160924e991319cd40735fae4cd34d1.jpg'),(2,4,'images/jy_ppp/xuni/4/563809fa513d2697cf819bda55fbb2fb4216d838.jpg'),(2,4,'images/jy_ppp/xuni/4/5dcc962bd40735faa044dd3e9e510fb30d2408d1.jpg'),(2,40,'images/jy_ppp/xuni/40/36dda3cc7cd98d1034b81146273fb80e7bec905c.jpg'),(2,40,'images/jy_ppp/xuni/40/b4af2edda3cc7cd9b94129ae3f01213fb80e915c.jpg'),(2,41,'images/jy_ppp/xuni/41/10fa513d269759ee30a25cc7b4fb43166c22dfcf.jpg'),(2,41,'images/jy_ppp/xuni/41/493d269759ee3d6d5b34bbc745166d224e4adecf.jpg'),(2,41,'images/jy_ppp/xuni/41/7b2762d0f703918fa82301c6573d269758eec4cf.jpg'),(2,41,'images/jy_ppp/xuni/41/b8ec08fa513d26975521345153fbb2fb4216d8cf.jpg'),(2,42,'images/jy_ppp/xuni/42/bf86c9177f3e6709af4d4a883dc79f3df8dc5573.jpg'),(2,42,'images/jy_ppp/xuni/42/c9c8a786c9177f3e0279f37a76cf3bc79f3d5673.jpg'),(2,42,'images/jy_ppp/xuni/42/d1177f3e6709c93d17bf0180993df8dcd1005473.jpg'),(2,43,'images/jy_ppp/xuni/43/1b4f78f0f736afc3e8cb4010b519ebc4b64512fb.jpg'),(2,43,'images/jy_ppp/xuni/43/2ca85edf8db1cb137e6b4266db54564e92584b40.jpg'),(2,43,'images/jy_ppp/xuni/43/945494eef01f3a290248aabb9f25bc315d607cc6.jpg'),(2,43,'images/jy_ppp/xuni/43/a2a1cd11728b4710d9e982b9c5cec3fdfd032384.jpg'),(2,44,'images/jy_ppp/xuni/44/945494eef01f3a29d7249fc09f25bc315d607cd4.jpg'),(2,44,'images/jy_ppp/xuni/44/ac8f8c5494eef01fc3f852cbe6fe9925bd317dd4.jpg'),(2,44,'images/jy_ppp/xuni/44/b751f3deb48f8c54633f8f213c292df5e1fe7fd4.jpg'),(2,44,'images/jy_ppp/xuni/44/bfefce1b9d16fdfa5c808ce0b28f8c5495ee7bd4.jpg'),(2,44,'images/jy_ppp/xuni/44/e5faaf51f3deb48f7a85ebd0f61f3a292cf578d4.jpg'),(2,45,'images/jy_ppp/xuni/45/393fb80e7bec54e7a9aeeb6bbf389b504fc26a01.png'),(2,45,'images/jy_ppp/xuni/45/63ec54e736d12f2e18475f1549c2d56285356801.png'),(2,45,'images/jy_ppp/xuni/45/a00e7bec54e736d18f517d7d9d504fc2d5626901.png'),(2,46,'images/jy_ppp/xuni/46/1246f21fbe096b6316c822270a338744eaf8acf0.jpg'),(2,46,'images/jy_ppp/xuni/46/ea1fbe096b63f6248a8c45748144ebf81b4ca3f0.jpg'),(2,47,'images/jy_ppp/xuni/47/393fb80e7bec54e789d68b1dbf389b504fc26aff.jpg'),(2,47,'images/jy_ppp/xuni/47/6bf082025aafa40f7b705fdead64034f78f0197a.jpg'),(2,48,'images/jy_ppp/xuni/48/024c510fd9f9d72a4e85216ad22a2834359bbbe7.jpg'),(2,48,'images/jy_ppp/xuni/48/14338744ebf81a4c7ad3ddbed12a6059242da6e7.jpg'),(2,48,'images/jy_ppp/xuni/48/490fd9f9d72a60590af1d06d2e34349b023bbae7.jpg'),(2,48,'images/jy_ppp/xuni/48/73600c338744ebf830905548dff9d72a6159a7e7.jpg'),(2,48,'images/jy_ppp/xuni/48/9f44ebf81a4c510ff525d36d6659252dd52aa5e7.jpg'),(2,49,'images/jy_ppp/xuni/49/05950a7b02087bf488c6e69bf4d3572c11dfcf9e.jpg'),(2,49,'images/jy_ppp/xuni/49/8516fdfaaf51f3de45540f1692eef01f3b2979da.jpg'),(2,49,'images/jy_ppp/xuni/49/ead3572c11dfa9ec03c9e06564d0f703908fc1d9.jpg'),(2,5,'images/jy_ppp/xuni/5/3034349b033b5bb5e1b6048930d3d539b600bc67.jpg'),(2,50,'images/jy_ppp/xuni/50/5934970a304e251ffcf6e08fa186c9177f3e5347.jpg'),(2,50,'images/jy_ppp/xuni/50/8f0a304e251f95cabbf496c1cf177f3e67095247.jpg'),(2,50,'images/jy_ppp/xuni/50/b0d3fd1f4134970a5772145893cad1c8a7865d47.jpg'),(2,51,'images/jy_ppp/xuni/51/b0d3fd1f4134970a5a3c0b5893cad1c8a7865d7d.jpg'),(2,51,'images/jy_ppp/xuni/51/e51f4134970a304e4e6dbb8dd7c8a786c9175c7d.jpg'),(2,52,'images/jy_ppp/xuni/52/14f431adcbef760964f084e828dda3cc7dd99ec9.jpg'),(2,52,'images/jy_ppp/xuni/52/29adcbef76094b369c69069aa5cc7cd98c109dc9.jpg'),(2,52,'images/jy_ppp/xuni/52/d3ef76094b36acaf1f1b8b8b7ad98d1000e99cc9.jpg'),(2,53,'images/jy_ppp/xuni/53/7481800a19d8bc3e75a2ffbf848ba61ea8d34519.jpg'),(2,53,'images/jy_ppp/xuni/53/8482d158ccbf6c81419dd59dba3eb13533fa4019.jpg'),(2,53,'images/jy_ppp/xuni/53/abde9c82d158ccbfac164c4f1fd8bc3eb1354119.jpg'),(2,53,'images/jy_ppp/xuni/53/c958ccbf6c81800adb4f707bb73533fa828b4719.jpg'),(2,54,'images/jy_ppp/xuni/54/14d7912397dda144680bd3bcb4b7d0a20cf486ba.jpg'),(2,54,'images/jy_ppp/xuni/54/9d35e5dde71190ef95f92dd1c81b9d16fdfa60ba.jpg'),(2,55,'images/jy_ppp/xuni/55/0dce36d3d539b6000c3a32c0ef50352ac75cb7d4.png'),(2,55,'images/jy_ppp/xuni/55/cd39b6003af33a87d1993d6dc05c10385243b5d4.png'),(2,56,'images/jy_ppp/xuni/56/1b4f78f0f736afc38eb2ee10b519ebc4b745120d.png'),(2,57,'images/jy_ppp/xuni/57/8a45d688d43f8794d66f3c59d41b0ef41bd53a30.jpg'),(2,57,'images/jy_ppp/xuni/57/ce88d43f8794a4c20eb3935c08f41bd5ad6e3930.jpg'),(2,58,'images/jy_ppp/xuni/58/7ad0f703918fa0ec69b1e079209759ee3d6ddb54.jpg'),(2,58,'images/jy_ppp/xuni/58/7b2762d0f703918fc0a7b9be573d269759eec454.jpg'),(2,58,'images/jy_ppp/xuni/58/ef03918fa0ec08fa337697d35fee3d6d55fbda54.jpg'),(2,59,'images/jy_ppp/xuni/59/9f44ebf81a4c510fea392a096659252dd42aa520.jpg'),(2,59,'images/jy_ppp/xuni/59/f3f81a4c510fd9f9e3ea9d7a232dd42a2834a420.jpg'),(2,6,'images/jy_ppp/xuni/6/2cec872bd40735faea973b0b9e510fb30e240814.jpg'),(2,6,'images/jy_ppp/xuni/6/342a8c82d158ccbfbff4c08819d8bc3eb0354128.jpg'),(2,6,'images/jy_ppp/xuni/6/55ef1ef41bd5ad6edd9e115881cb39dbb7fd3c14.jpg'),(2,6,'images/jy_ppp/xuni/6/d62e91cb39dbb6fd573c90940924ab18962b3714.jpg'),(2,6,'images/jy_ppp/xuni/6/fb2d9d1001e93901874af88c7bec54e737d19628.jpg'),(2,6,'images/jy_ppp/xuni/6/ff04e736afc37931290ff39bebc4b74542a91114.jpg'),(2,60,'images/jy_ppp/xuni/60/16f41bd5ad6eddc4c6bba2883fdbb6fd52663360.jpg'),(2,60,'images/jy_ppp/xuni/60/651ed21b0ef41bd5380ffe8757da81cb39db3d60.jpg'),(2,60,'images/jy_ppp/xuni/60/ca1b0ef41bd5ad6e4ba5729987cb39dbb6fd3c60.jpg'),(2,61,'images/jy_ppp/xuni/61/de5c10385343fbf20f141a57b67eca8065388f8e.jpg'),(2,62,'images/jy_ppp/xuni/62/6bf082025aafa40f350fadabad64034f79f019fe.png'),(2,62,'images/jy_ppp/xuni/62/b54bd11373f08202265bf2494dfbfbedaa641bfe.png'),(2,62,'images/jy_ppp/xuni/62/c91373f082025aafd9fb1dbdfdedab64024f1afe.png'),(2,63,'images/jy_ppp/xuni/63/1f0828381f30e924327ddce949086e061c95f709.jpg'),(2,63,'images/jy_ppp/xuni/63/862f070828381f3060c0cd71ac014c086f06f009.jpg'),(2,63,'images/jy_ppp/xuni/63/b98b87d6277f9e2f81ec5dd01a30e924b999f309.jpg'),(2,64,'images/jy_ppp/xuni/64/1b3b5bb5c9ea15ce3c77e400b0003af33a87b219.jpg'),(2,64,'images/jy_ppp/xuni/64/2c9b033b5bb5c9ea1c6a07ead339b6003af3b319.jpg'),(2,65,'images/jy_ppp/xuni/65/1f0828381f30e924df063e394a086e061d95f71c.jpg'),(2,65,'images/jy_ppp/xuni/65/7481800a19d8bc3e4895a4c2848ba61ea8d3451d.jpg'),(2,65,'images/jy_ppp/xuni/65/8516fdfaaf51f3de69131b6c92eef01f3a297911.jpg'),(2,65,'images/jy_ppp/xuni/65/bbcc7cd98d1001e9ff61b907be0e7bec54e7975d.jpg'),(2,65,'images/jy_ppp/xuni/65/f3f81a4c510fd9f94db4f761232dd42a2834a413.jpg'),(2,66,'images/jy_ppp/xuni/66/1246f21fbe096b63ed4265480a338744ebf8ac4a.jpg'),(2,66,'images/jy_ppp/xuni/66/63cb0a46f21fbe097105f80c6d600c338744ad4a.jpg'),(2,67,'images/jy_ppp/xuni/67/8f0a304e251f95ca014f6ca4cf177f3e6709526e.jpg'),(2,67,'images/jy_ppp/xuni/67/ab19ebc4b74543a9943a04de18178a82b90114fa.jpg'),(2,67,'images/jy_ppp/xuni/67/f8fe9925bc315c6087e395fd8bb1cb13495477aa.jpg'),(2,68,'images/jy_ppp/xuni/68/5f10b912c8fcc3ce70fe06459445d688d43f208e.jpg'),(2,68,'images/jy_ppp/xuni/68/a112c8fcc3cec3fd48006803d088d43f8794278e.jpg'),(2,68,'images/jy_ppp/xuni/68/d0fcc3cec3fdfc0327462cced23f8794a4c2268e.jpg'),(2,69,'images/jy_ppp/xuni/69/1124ab18972bd4072b4f40ea7d899e510fb30918.jpg'),(2,69,'images/jy_ppp/xuni/69/4a66d0160924ab188b9e722033fae6cd7b890b18.jpg'),(2,7,'images/jy_ppp/xuni/7/1c5f9f2f0708283842db68b0b899a9014d08f164.jpg'),(2,7,'images/jy_ppp/xuni/7/1e3f94cad1c8a78637ddfeaa6709c93d71cf50bb.jpg'),(2,7,'images/jy_ppp/xuni/7/5c29c83d70cf3bc79bcc7948d100baa1cc112a61.jpg'),(2,7,'images/jy_ppp/xuni/7/8faf8d5494eef01fe4a55179e0fe9925bd317dfd.jpg'),(2,7,'images/jy_ppp/xuni/7/9a64ac345982b2b763168d6031adcbef77099bad.jpg'),(2,70,'images/jy_ppp/xuni/70/8482d158ccbf6c815161a59eba3eb13533fa4076.jpg'),(2,70,'images/jy_ppp/xuni/70/abde9c82d158ccbfbcea3c4c1fd8bc3eb1354176.jpg'),(2,70,'images/jy_ppp/xuni/70/c958ccbf6c81800acbb30078b73533fa828b4776.jpg'),(2,71,'images/jy_ppp/xuni/71/57c2d5628535e5dd4ebc52d670c6a7efce1b6223.jpg'),(2,71,'images/jy_ppp/xuni/71/83504fc2d56285354d70252896ef76c6a7ef6323.jpg'),(2,71,'images/jy_ppp/xuni/71/cd628535e5dde7113a42b4ffa1efce1b9d166123.jpg'),(2,72,'images/jy_ppp/xuni/72/asd234sdfdsf.jpg'),(2,72,'images/jy_ppp/xuni/72/bvjbhkihoudfgdw.jpg'),(2,72,'images/jy_ppp/xuni/72/tyutyiyuouiouio.jpg'),(2,72,'images/jy_ppp/xuni/72/uhy8iczxzfesr34.jpg'),(2,72,'images/jy_ppp/xuni/72/vbncjnczxkczxjgdsafwq.jpg'),(2,72,'images/jy_ppp/xuni/72/xcjgkhdfghdfkhgfgh.jpg'),(2,72,'images/jy_ppp/xuni/72/zxcnzxnfndgdfgk.jpg'),(2,73,'images/jy_ppp/xuni/73/218732qh4hsdaf.jpg'),(2,73,'images/jy_ppp/xuni/73/28sadyufsudfhdsfh.jpg'),(2,73,'images/jy_ppp/xuni/73/87dasugdgashf.jpg'),(2,73,'images/jy_ppp/xuni/73/asd8932jdsafhsd.jpg'),(2,73,'images/jy_ppp/xuni/73/ausd6rw34rhwezxbc.jpg'),(2,73,'images/jy_ppp/xuni/73/jj54345retvxsdf.jpg'),(2,73,'images/jy_ppp/xuni/73/sadwq87324234.jpg'),(2,74,'images/jy_ppp/xuni/74/2ed12f2eb9389b5057e09a258335e5dde7116ead.jpg'),(2,74,'images/jy_ppp/xuni/74/4ce736d12f2eb9388c720085d3628535e5dd6fad.jpg'),(2,74,'images/jy_ppp/xuni/74/4dfbb2fb43166d22ab1617e2402309f79052d267.jpg'),(2,75,'images/jy_ppp/xuni/75/22f8bd3eb13533fa7082248aa8d3fd1f41345b7a.jpg'),(2,75,'images/jy_ppp/xuni/75/441e6609c93d70cf38361ea9f8dcd100bba12b61.jpg'),(2,75,'images/jy_ppp/xuni/75/57a1810a19d8bc3e413cb16e828ba61ea8d3457a.jpg'),(2,75,'images/jy_ppp/xuni/75/883c8601a18b87d67e941fbb070828381e30fd64.jpg'),(2,75,'images/jy_ppp/xuni/75/9d3ea9d3fd1f41346003b2da251f95cad1c85e7a.jpg'),(2,76,'images/jy_ppp/xuni/76/883c8601a18b87d679f8e2a3070828381e30fdf0.jpg'),(2,76,'images/jy_ppp/xuni/76/9471f2deb48f8c5448628c933a292df5e1fe7ffd.jpg'),(2,76,'images/jy_ppp/xuni/76/aeead0c8a786c91780f4e69dc93d70cf3ac757bb.jpg'),(2,76,'images/jy_ppp/xuni/76/bb2a18d8bc3eb135c2f3001fa61ea8d3fd1f447a.jpg'),(2,76,'images/jy_ppp/xuni/76/bc21a08b87d6277fc1a87b8428381f30e824fcf0.jpg'),(2,77,'images/jy_ppp/xuni/77/c3fcd000baa1cd117cf53b9cb912c8fcc2ce2dee.jpg'),(2,77,'images/jy_ppp/xuni/77/c8feb58f8c5494ee2d8646a52df5e0fe98257e82.jpg'),(2,77,'images/jy_ppp/xuni/77/c8feb58f8c5494ee2d9346a52df5e0fe98257efd.jpg'),(2,77,'images/jy_ppp/xuni/77/f79f6d81800a19d8533733a133fa828ba61e467a.jpg'),(2,78,'images/jy_ppp/xuni/78/6b1cb522g9074917214a6&690.jpg'),(2,78,'images/jy_ppp/xuni/78/6b1cb522g907497a7efeb&690.jpg'),(2,79,'images/jy_ppp/xuni/79/6b1cb522g9074a56a6961&690.jpg'),(2,79,'images/jy_ppp/xuni/79/6b1cb522g9074a5b6ad07&690.jpg'),(2,8,'images/jy_ppp/xuni/8/11f790529822720ec125828e7dcb0a46f31fabdf.jpg'),(2,8,'images/jy_ppp/xuni/8/38a4462309f7905253f4274a0af3d7ca7acbd5df.jpg'),(2,8,'images/jy_ppp/xuni/8/5e2309f790529822bed859b7d1ca7bcb0b46d4df.jpg'),(2,80,'images/jy_ppp/xuni/80/6b1cb522g9074919fdfd9&690.jpg'),(2,80,'images/jy_ppp/xuni/80/6b1cb522g907496b3d07f&690.jpg'),(2,81,'images/jy_ppp/xuni/81/b54bd11373f08202f0768c494dfbfbedaa641bdb.jpg'),(2,81,'images/jy_ppp/xuni/81/c943ad4bd11373f02bdb72e9a20f4bfbfaed04db.jpg'),(2,82,'images/jy_ppp/xuni/82/asdweyt8678342234.jpg'),(2,82,'images/jy_ppp/xuni/82/njghju675234sdsXa.jpg'),(2,83,'images/jy_ppp/xuni/83/2ed3d539b6003af34b2b2715332ac65c1038b636.jpg'),(2,83,'images/jy_ppp/xuni/83/xcfgrtyrwr345456zcsddfgfghghjyui787fghfg.jpg'),(2,84,'images/jy_ppp/xuni/84/4-150F6205644.jpg'),(2,84,'images/jy_ppp/xuni/84/4-150F6205645.jpg'),(2,84,'images/jy_ppp/xuni/84/4-150F6205A1.jpg'),(2,84,'images/jy_ppp/xuni/84/4-150F6205A3.jpg'),(2,85,'images/jy_ppp/xuni/85/1-150FG25938.jpg'),(2,85,'images/jy_ppp/xuni/85/1-150FG25940.jpg'),(2,85,'images/jy_ppp/xuni/85/1-150FG25946.jpg'),(2,85,'images/jy_ppp/xuni/85/1-150FG25953.jpg'),(2,86,'images/jy_ppp/xuni/86/4-150F6204Z0.jpg'),(2,86,'images/jy_ppp/xuni/86/4-150F6204Z1.jpg'),(2,87,'images/jy_ppp/xuni/87/1-150F6140648.jpg'),(2,87,'images/jy_ppp/xuni/87/1-150F6140A5.jpg'),(2,87,'images/jy_ppp/xuni/87/1-150F6140A9.jpg'),(2,88,'images/jy_ppp/xuni/88/1-150F5110T0.jpg'),(2,88,'images/jy_ppp/xuni/88/1-150F5110T2.jpg'),(2,88,'images/jy_ppp/xuni/88/1-150F5110T5.jpg'),(2,89,'images/jy_ppp/xuni/89/1-1505210S00gfg5-50.jpg'),(2,89,'images/jy_ppp/xuni/89/1-1505ertert210S007.jpg'),(2,89,'images/jy_ppp/xuni/89/1-1505sdf210S003.jpg'),(2,89,'images/jy_ppp/xuni/89/1-1505sdfxv210S005.jpg'),(2,89,'images/jy_ppp/xuni/89/1-150cvbcvb5210S006.jpg'),(2,9,'images/jy_ppp/xuni/9/4a66d0160924ab18fbcd824033fae6cd7a890be9.jpg'),(2,9,'images/jy_ppp/xuni/9/57c2d5628535e5ddf37ec7a870c6a7efce1b6272.jpg'),(2,9,'images/jy_ppp/xuni/9/63f40ad162d9f2d3e5424698afec8a136327cc72.jpg'),(2,90,'images/jy_ppp/xuni/90/1-1505fdbcvnb1FP540.jpg'),(2,91,'images/jy_ppp/xuni/91/1-1505150K151-50.jpg'),(2,91,'images/jy_ppp/xuni/91/1-1505150K152.jpg'),(2,92,'images/jy_ppp/xuni/92/1-1505130KZ5-50.jpg'),(2,92,'images/jy_ppp/xuni/92/1-1505130KZ5.jpg'),(2,92,'images/jy_ppp/xuni/92/1-1505130KZ7.jpg'),(2,92,'images/jy_ppp/xuni/92/1-1505130KZ8.jpg'),(2,93,'images/jy_ppp/xuni/93/1-1505120Kfghfgh928-50.jpg'),(2,93,'images/jy_ppp/xuni/93/1-150512sdxz0K926.jpg'),(2,94,'images/jy_ppp/xuni/94/1-1505110K918-50.jpg'),(2,94,'images/jy_ppp/xuni/94/1-1505110K918.jpg'),(2,94,'images/jy_ppp/xuni/94/1-1505110K922.jpg'),(2,95,'images/jy_ppp/xuni/95/1-15050ZzxcvQ910-50.jpg'),(2,96,'images/jy_ppp/xuni/96/1-15050PI4fdfd57.jpg'),(2,96,'images/jy_ppp/xuni/96/1-15050PIzxzc501.jpg'),(2,96,'images/jy_ppp/xuni/96/1-15050dfsadfPI458.jpg'),(2,96,'images/jy_ppp/xuni/96/1-15050xcvxcvPI459.jpg'),(2,97,'images/jy_ppp/xuni/97/1-15050Gfhfgh02R2.jpg'),(2,97,'images/jy_ppp/xuni/97/1-15050Gzxczxc02R4-50.jpg'),(2,97,'images/jy_ppp/xuni/97/1-1505asdasd0G02R5.jpg'),(2,98,'images/jy_ppp/xuni/98/1-1505060Q302.jpg'),(2,98,'images/jy_ppp/xuni/98/1-1505060Q303-50.jpg'),(2,98,'images/jy_ppp/xuni/98/1-1505060Q303.jpg'),(2,98,'images/jy_ppp/xuni/98/1-1505060Q304.jpg'),(2,98,'images/jy_ppp/xuni/98/1-1505060Q305.jpg'),(2,99,'images/jy_ppp/xuni/99/1-1505050Q101.jpg'),(2,99,'images/jy_ppp/xuni/99/1-1505050Q107.jpg');


	        	";
	        	pdo_query($sql);
	        	message("导入成功",$this->createWebUrl ( 'xunithumb', array ('op' => 'display') ), 'success' );
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition='';
			if(!empty($_GPC['sex']))
			{
				$condition.=" WHERE sex=".intval($_GPC['sex']);
			}
			$category = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_xunithumb' ) .$condition." GROUP BY mid ORDER BY mid DESC LIMIT ".($pindex - 1) * $psize.",{$psize}" );
			$total=pdo_fetchall("SELECT mid FROM ".tablename('jy_ppp_xunithumb').$condition." GROUP BY mid ");
			$total=count($total);
			if(!empty($category))
			{
				foreach ($category as $key => $value) {
					$temp=pdo_fetch("SELECT thumb FROM ".tablename('jy_ppp_xunithumb')." WHERE mid=".$value['mid']." AND avatar=1 ");
					if(!empty($temp))
					{
						$category[$key]['thumb']=$temp['thumb'];
					}
					else
					{
						$temp['thumb']=$value['thumb'];
					}
					$category[$key]['xc']=pdo_fetchall("SELECT thumb FROM ".tablename('jy_ppp_xunithumb')." WHERE mid=".$value['mid']." AND avatar=0 AND thumb!='".$temp['thumb']."'");
				}
			}
			$pager = pagination($total, $pindex, $psize);
			include $this->template ( 'web/xunithumb' );
		}
		elseif ($operation == 'delall') {
			$str=$_GPC['str'];
			if(!empty($str))
			{
				$str=substr($str, 0 , -1);
			}
			$str_arr=explode(',', $str);
			foreach ($str_arr as $key => $value) {
				pdo_delete ( 'jy_ppp_xunithumb', array (
					'mid' => $value 
			) );
			}
			message ( '虚拟用户相册设置删除成功！', $this->createWebUrl ( 'xunithumb', array (
					'op' => 'display' 
			) ), 'success' );
		}
		 elseif ($operation == 'post') {
			$mid = intval ( $_GPC ['mid'] );
			load()->func('tpl');
			if (! empty ( $mid )) {
				$avatar=pdo_fetch( "SELECT * FROM " . tablename ( 'jy_ppp_xunithumb' ) . " WHERE mid = ".$mid." AND avatar=1 " );
				if(!empty($avatar))
				{
					$category['sex'] = $avatar['sex'];
					$avatar=$avatar['thumb'];
					$category_arr = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_xunithumb' ) . " WHERE mid = ".$mid." AND avatar=0 " );
					
				}
				else
				{
					$category_arr = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_xunithumb' ) . " WHERE mid = '$mid'" );
					if(!empty($category_arr))
					{
						$avatar=$category_arr[0]['thumb'];
						$category['sex'] = $category_arr[0]['sex'] ;
						unset($category_arr[0]);
					}
					else
					{
						$category['sex'] = 1 ;
					}
				}
				if(!empty($category_arr))
				{
					$category['thumb']=array();
					foreach ($category_arr as $key => $value) {
						array_push($category['thumb'], $value['thumb']);
					}
				}
				
			} else {
				$category = array (
						'thumb' => '',
						'sex' => 2
				);
			}
			if (checksubmit ( 'submit' )) {
				$avatar=$_GPC['avatar'];
				$sex=intval ( $_GPC ['sex'] );
				$thumb =$_GPC ['thumb'];
				if(empty($thumb) && empty($avatar))
				{
					message("请至少上传一张图片！");
				}
				else
				{
					if(!empty($thumb))
					{
						if(empty($avatar))
						{
							$avatar=$thumb[0];
							unset($thumb[0]);
						}
					}

					if(empty($mid))
					{
						$llll=pdo_fetch("SELECT mid FROM ".tablename('jy_ppp_xunithumb')." ORDER BY mid DESC LIMIT 1 ");
						if(!empty($llll))
						{
							$mid=$llll['mid']+1;
						}
						else
						{
							$mid=1;
						}
					}
					else
					{
						$mem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_xunithumb')." WHERE mid=".$mid." LIMIT 1");
						if($mem['sex']!=$sex)
						{
							pdo_update("jy_ppp_xunithumb",array('sex'=>$sex),array('mid'=>$mid));
						}
					}
					$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_xunithumb')." WHERE mid=".$mid." AND thumb='".$avatar."'");
					if(!empty($temp))
					{
						pdo_update("jy_ppp_xunithumb",array('avatar'=>0),array('mid'=>$mid));
						pdo_update("jy_ppp_xunithumb",array('avatar'=>1),array('id'=>$temp['id']));
					}
					else
					{
						$data = array (
							'avatar' => 1,
							'sex' => $sex,
							'mid'=>$mid,
							'thumb' => $avatar,
						);
						pdo_insert('jy_ppp_xunithumb',$data);
					}
					if(!empty($thumb))
					{
						pdo_delete("jy_ppp_xunithumb",array('mid'=>$mid,'avatar'=>0));
						foreach ($thumb as $key => $value) {

								$data = array (
									'avatar' => 0,
									'sex' => $sex,
									'mid'=>$mid,
									'thumb' => $value,
								);
								pdo_insert('jy_ppp_xunithumb',$data);
						}
					}

				}

				message ( '更新虚拟用户相册设置成功！', $this->createWebUrl ( 'xunithumb', array ('op' => 'display' ) ), 'success' );
			}
			include $this->template ( 'web/xunithumb' );
		} elseif ($operation == 'delete') {
			$mid = intval ( $_GPC ['mid'] );
			$category = pdo_fetch ( "SELECT mid FROM " . tablename ( 'jy_ppp_xunithumb' ) . " WHERE mid = '$mid'" );
			if (empty ( $category )) {
				message ( '抱歉，虚拟用户相册不存在或是已经被删除！', $this->createWebUrl ( 'xunithumb', array (
						'op' => 'display' 
				) ), 'error' );
			}
			pdo_delete ( 'jy_ppp_xunithumb', array (
					'mid' => $mid 
			) );
			message ( '虚拟用户相册设置删除成功！', $this->createWebUrl ( 'xunithumb', array (
					'op' => 'display' 
			) ), 'success' );
		}