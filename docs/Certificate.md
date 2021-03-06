# NFePHP\Common\Certificate::class

Esta classe é responsável por tratar a utilizar os certificados digitais modelo A1 (PKCS#12).

Com o uso dessas classes não mais é necessário que existam certificados em arquivo, ou seja você pode optar por manter os certificados em base de dados, arquivo, arquivo remoto, repositório ou qualquer outra forma que deseje.

Para usar um certificado PFX basta obter seu conteudo e passar para a classe com sua respectiva senha.

# DEPENDÊNCIAS

[NFePHP\Common\Certificate\PrivateKey](Certificate\PrivateKey.md)

[NFePHP\Common\Certificate\PublicKey](Certificate\PublicKey.md)

[NFePHP\Common\Certificate\CertificationChain](Certificate\CertificationChain.md)

[NFePHP\Common\Certificate\Asn1](Certificate\Asn1.md)

# PROPRIEDADES

## public $privateKey
Instância de [PrivateKey::class](Certificate/PrivateKey.md)

## public $publicKey;
Instância de [PublicKey::class](Certificate/PublicKey.md)

## public $chainKeys;
Instância de [CertificationChain::class](Certificate/CertificationChain.md)

# USO

```php
use NFePHP\Common\Certificate;
use NFePHP\Common\Certificate\CertificationChain;


$pfx = file_get_contents('<PATH TO PFX FILE>');
$cert = Certificate::readPfx($pfx, '<PASSWORD>');

//carrega a cadeia de certificados, usar apenas se necessário
$strchain = file_get_contents('<PATH TO CHAIN IN PEM FORMAT>');
$chain = new CertificationChain($strchain);

$cert->chainKeys = $chain;
```

# MÉTODOS

## public function __construct(PrivateKey $privateKey, PublicKey $publicKey, CertificationChain $chainKeys = null)::this

- $privateKey = Instância de [PrivateKey::class](Certificate/PrivateKey.md)
- $publicKey = Instância de [PublicKey::class](Certificate/PublicKey.md)
- $chainKeys = Instância de [CertificationChain::class](Certificate/CertificationChain.md)

```php
//anuncio
use NFePHP\Common\Certificate\PrivateKey;
use NFePHP\Common\Certificate\PublicKey;
use NFePHP\Common\Certificate\CertificationChain;

//pre carregamento
$priKey = new PrivateKey($privatekeyContent);
$pubKey = new PublicKey($publickeyContent);
$chain = new CertificationChain($chainkeysContent);

//instanciação
$certs = new Certificate($priKey, $pubKey, $chain);

```

## public static function readPfx($content, $password)::this

Alternativamente essa classe pode ser carregada estaticamente com a chamada readPfx(), onde:

$content = conteudo do arquivo PFX
$password = senha de acesso ao certificado

NOTA: caso ocorra algum erro será disparada uma EXCEPTION

```php
use NFePHP\Common\Certificate;
use NFePHP\Common\Exception\CertificateException;

try {

    $cert = Certificate::readPfx($content, $password);

} catch (CertificateException $e) {
    //aqui você trata a exceção
}
```

## public function writePfx($password)::string

Esse método permite que o PFX seja recriado com base em sua chave publica, privada e irá incluir toda a cadeia de certificação, se fornecida.

$password = senha de acesso ao certificado pfx, parametro obrigatório

```php

$novopfx = $cert->writePfx('senha');

```


## public function getCompanyName()::string

Método irá retorna a Razão Social gravada no certificado

```php

$razao = $cert->getCompanyName();

```

## public function getValidFrom()::\DateTime

Método irá retornar uma classe \DateTime com a a data de inicio da validade, ou seja a data de criação do certificado.

```php

$validFrom = $cert->getValidFrom();

echo $validFrom->format('Y-m-d');

```

## public function getValidTo()::\DateTime

Método irá retornar uma classe \DateTime com a a data de FIM da validade, ou seja a data limite de uso, em geral um ano após a emissão.

```php

$validFrom = $cert->getValidTo();

echo $validTo->format('Y-m-d');

```

## public function isExpired()::bool

Método irá retornar TRUE, se o certificado tiver a data expirada ou seja não está mais válido
ou FALSE se ainda estiver válido.

```php

if ($cert->isExpired()) {
    echo "Certificado VENCIDO! Não é possivel mais usa-lo";
} else {
    echo "Certificado VALIDO!";
}

```

## public function getCnpj()::string

Método irá retornar o numero do CNPJ

```php

echo "CNPJ: " . $cert->getCnpj();

```

## public function sign($content, $algorithm = OPENSSL_ALGO_SHA1)::string

Este método cria a assinatura digital usando a chave Privada.

> NOTA: usualmente é usado o algoritimo OPENSSL_ALGO_SHA1, mas existem casos em que poderemos ter que usar outros algoritimos como o OPENSSL_ALGO_SHA256, por exemplo.

```php

$content = "dados a serem assinados";

echo base64_encode($cert->sign($content, OPENSSL_ALGO_SHA1));
//o retorno foi convertido para base64 pois contêm dados binários

```

## public function verify($data, $signature, $algorithm = OPENSSL_ALGO_SHA1)

Este método valida a assinatura usando a chave Publica.

```php

$data = "dados a serem assinados";
$signature = "rleddaKS731zeLAFuhpXOglVm2UOlAbWxZNvZbNS5NueumeGBSCmxuuYcubUCTgoB+RJzPIzU45eUbfN8B41q+WPWmsyQcWslm7geTyCrWnCJNaYGq5cVJ5eCqTRErQYSo/pBVizDLqyn+UmGUxhn+73sVlPM0kFqiFPpRCmG3azxRD60X48PDi42wvtxbe47FGZuj0XeRqoUvEra2FZPDxoYYrZqvRVHxzZtRpi+Wvp3FcbF+0WsxNgg9xXi4+TgfGDbrOlbx0PxhrvZAWvkKZTiSBKxqvYgeXgIk9KNLkm0UG/u8Gk5DLVEuC3QIdsVcl+dFPapXf0JJIAa4OpjQ==";
//a assinatura foi convertida para base64 pois contêm caracteres binários
if ($cert->verify($data, base64_decode($signature), OPENSSL_ALGO_SHA1)) {
    echo "Assinatura Confere !!!";
} else {
    echo "ERRO. A assinatura NÃO confere";
}

```


## public function __toString()::string

Este método retorna a chave publica e a cadeia de certificação, se houver em uma string no formato PEM 

```php

echo "{$cert}";

```

