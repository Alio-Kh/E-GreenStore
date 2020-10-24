<?php

namespace App\Service\Impl;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Repository\ClientRepository;
use App\Service\PaymentService;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use App\Connect2PayClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PaymentServiceImpl  extends AbstractController implements PaymentService
{

    private $c2pClient;
    private $connect2pay = "https://paiement.payzone.ma/";
    private $merchant = "106732";
    private $password = "GlB167@aaecAecdC";
    private $redirectURL = "http://127.0.0.1:8001/";
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->c2pClient = new Connect2PayClient($this->connect2pay, $this->merchant, $this->password);
    }
    public function getUrlAccessClient($amount)
    {
        // $client = new Client();
        $client = $this->clientRepository->findOneBy(array('id' => $this->getUser()->getClient()->getId()));

        if (isset($this->validateSSLCertificate) && $this->validateSSLCertificate == true) {
            $this->c2pClient->forceSSLValidation();
        }

        $currency = "MAD";

        // Transaction data
        $this->c2pClient->setOrderID(date("Y-m-d-H.i.s"));
        $this->c2pClient->setPaymentType((isset($paymentType)) ? $paymentType : Connect2PayClient::_PAYMENT_TYPE_CREDITCARD);
        if (isset($operation)) {
            $this->c2pClient->setOperation($operation);
        }
        $this->c2pClient->setPaymentMode(Connect2PayClient::_PAYMENT_MODE_SINGLE);
        $this->c2pClient->setShopperID($client->getReference());
        $this->c2pClient->setShippingType(Connect2PayClient::_SHIPPING_TYPE_VIRTUAL);
        $this->c2pClient->setAmount($amount);
        $this->c2pClient->setOrderDescription("Test purchase of fake product.");
        $this->c2pClient->setCurrency($currency);
        $this->c2pClient->setShopperFirstName($client->getPrenom());
        $this->c2pClient->setShopperLastName($client->getNom());
        $this->c2pClient->setShopperAddress($client->getAdresse());
        $this->c2pClient->setShopperZipcode("NA");
        $this->c2pClient->setShopperCity("NA");
        $this->c2pClient->setShopperCountryCode("MA");
        $this->c2pClient->setShopperPhone($client->getTele());
        $this->c2pClient->setCtrlRedirectURL($this->redirectURL);
        $this->c2pClient->setShopperEmail($this->getUser()->getEmail());
        //   $this->c2pClient->setOrderTotalWithoutShipping($amount - 50);

        // if (isset($addCartProducts) && $addCartProducts) {
        //     $product = new CartProduct();
        //     $product->setCartProductId(1345)->setCartProductName("Test Product");
        //     $product->setCartProductUnitPrice(456)->setCartProductQuantity(1);
        //     $product->setCartProductBrand("Yellow Thumb")->setCartProductMPN("NA");
        //     $product->setCartProductCategoryName("Led screen")->setCartProductCategoryID(1234);
        //     $this->c2pClient->addCartProduct($product);

        //     $product = new CartProduct();
        //     $product->setCartProductId(6789)->setCartProductName("Test Product 2");
        //     $product->setCartProductUnitPrice(123)->setCartProductQuantity(1);
        //     $product->setCartProductBrand("Yellow Thumb")->setCartProductMPN("NA");
        //     $product->setCartProductCategoryName("DVD reader")->setCartProductCategoryID(1235);
        //     $this->c2pClient->addCartProduct($product);
        //   }

        if ($this->c2pClient->validate()) {
            if ($this->c2pClient->preparePayment()) {
                return $this->c2pClient->getCustomerRedirectURL();
                //   echo "Result code:" . $this->c2pClient->getReturnCode() . "\n";
                //   echo "Result message:" . $this->c2pClient->getReturnMessage() . "\n";
                //   echo "Get merchant status by running: php cli-payment-status.php " . $this->c2pClient->getMerchantToken() . "\n";
                //   echo "Customer access is at: " . $this->c2pClient->getCustomerRedirectURL() . "\n";
                //   echo "To test the decryption of status posted when the customer is redirected, use the following command:\n";
                //   echo "php cli-encrypted-status.php " . $this->c2pClient->getMerchantToken() . ' ${data_field_from_the_form}' . "\n";
            } else {

                //   echo "preparePayment:" . $this->c2pClient->preparePayment() . "\n";
                //   echo "Result code:" . $this->c2pClient->getReturnCode() . "\n";
                return "Preparation error occured: " . $this->c2pClient->getClientErrorMessage() . "\n";
            }
        } else {
            return "Validation error occured: " . $this->c2pClient->getClientErrorMessage() . "\n";
        }
    }
}
