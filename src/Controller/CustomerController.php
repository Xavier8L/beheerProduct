<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Discount;
use App\Entity\DiscountProduct;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Row;
use App\Form\CustomerType;
use App\Form\DiscountType;
use App\Form\SearchType;
use App\Repository\DiscountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Date;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customer", name="customerHome")
     */
    public function index(): Response
    {
        return $this->render('customer/customerHome.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }

    /**
     * @Route("/signin", name="app_signup")
     */
    public function addAcount(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class,$customer);
        $form -> handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {


            $password = $encoder->encodePassword($customer,$customer->getPassword());
            $customer->setPassword($password);
            $customer->setRoles(['ROLE_CUSTOMER']);
            $em =$this->getDoctrine()->getManager();
            $em ->persist($customer);
            $em ->flush();


            $this->addFlash('success',"Your account has been created");
            return $this->redirectToRoute('home');
        }

        return $this->render('customer/signup.html.twig', [
            'CustomerForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/customer/cart/add/{product_id}", name="addcart"))
     */
    public function addCart($product_id)
    {

        $products=$this->getDoctrine()->getRepository(Product::class)->find($product_id);
        $inCart = false;
        $session = new Session();
        if($session->has('cart'))
        {
            $cart=$session->get('cart');

        }else
        {
            $session->set('cart',[]);
            $cart = $session->get('cart');

        }

        $toBeBought=
            [
                'id'=>$product_id,
//                'discount'=>$dis,
                'price'=> $products->getPrice(),
                'name'=> $products->getName(),
//                'new_price' =>$products->getPrice()*(1-$dis/100),
                'amount'=> 1,
            ];


        foreach ($cart as $key=>$pro)
        {
            if($toBeBought['id']==$pro['id'])
            {

                $cart[$key]['amount'] ++;
                $inCart = true;
            }
        }
        if (!$inCart)
        {

            array_push($cart, $toBeBought);
        }

        $session ->set('cart',$cart);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/customer/cart", name="cart"))
     */
    public function cart()
    {

        $session = new Session();
        $session->set('isCheck',false);
        $cart=$session->get('cart');
        if($cart == null)
        {
            return $this->render('customer/cart.html.twig',['carts'=>0]);
        }else
            {
                return $this->render('customer/cart.html.twig',
                    [
                        'carts' => $cart,
                    ]);
            }

    }


    /**
     * @Route("/customer/cart/delete/{product_id}", name="deletecart"))
     */
    public function deletecart($product_id)
    {

        $session = new Session();
        $cart=$session->get('cart');


        foreach ($cart as $key=>$pro)
        {
            if($product_id == $pro['id'])
            {
                if($cart[$key]['amount'] > 1)
                {
                    $cart[$key]['amount'] --;
                }else
                {
                    unset($cart[$key]);
                }
            }
        }
        if($cart == null)
        {
            $session ->remove('cart');
        }else{
        $session ->set('cart',$cart);
        }
        return $this->redirectToRoute('cart');

    }

    /**
     * @Route("/customer/cart/empty}", name="empty_cart"))
     */
    public function emptyCart()
    {

        $session = new Session();
        $session->remove('cart');
        return $this->redirectToRoute('cart');

    }

    /**
     * @Route("/customer/code", name="code"))
     */
    public function code(Request $request)
    {
        $form = $this->createForm(DiscountType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $code = $this->getDoctrine()->getRepository(Discount::class)->findCode($data['code']);
            if ($data['discount'] == false) {
                return $this->redirectToRoute('order');
            } else {
                if ($code != null) {
                    return $this->redirectToRoute('discountCart',
                        [
                            'code' => $data['code']
                        ]);
                } else {
                    $this->addFlash('dangers', 'de code is fout');
                }
            }

        }
        return $this->render('customer/cartWithCode.html.twig',
            [
                "discountForm" => $form->createView()
            ]);
    }


    /**
     * @Route("/customer/discountCart", name="discountCart"))
     */
    public function discountcart(RequestStack $rStack)
    {

        $session = new Session();
        $cart=$session->get('cart');
        $discountCode=$rStack->getCurrentRequest()->query->get('code');
        $code= $this->getDoctrine()->getRepository(Discount::class)->findCode($discountCode);

        $result=[];
        foreach ($cart as $product)
        {
            $dp = $this->getDoctrine()->getRepository(DiscountProduct::class)->findOneBy(['product'=>$product['id'],'discount'=>$code]);
            if($dp!= null)
            {
                $percentage = ['percentage' => $code->getPercentage()];
               $result[] = array_merge($product, $percentage);
            }
            else
                {
                    $percentage = ['percentage' => null];
                    $result[] = array_merge($product, $percentage);
                }
        }
        $session->set('isCheck',true);
        return $this->render('customer/cart.html.twig', [
                    'carts' => $result,'code'=>$discountCode
                ]);

    }


    /**
     * @Route("/customer/order", name="order")
     */
    public function order(RequestStack $stack)
    {

        $discountCode=$stack->getCurrentRequest()->query->get('code');
        $code= $this->getDoctrine()->getRepository(Discount::class)->findCode($discountCode);
        $order = new Order();
        if($code != null)
        {
            $order->setDiscountCode($code);
            $order->setDiscountUsed(true);
        }else{
            $order->setDiscountUsed(false);
        }

        $order->setCustomer($this->getUser());
        $order->setDate(new \DateTime());
        $em =$this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();
        $session = new Session();
        $cart=$session->get('cart');
        for($x=0; $x<count($cart); $x++)
        {
            $item=$cart[$x];
            $product= $this->getDoctrine()->getRepository(Product::class)->find($item['id']);
            $row = new Row();
            $row->setAmount($item['amount']);
            $row->setOrder2($order);
            $row->setProduct($product);
            $em =$this->getDoctrine()->getManager();
            $em->persist($row);
            $em->flush();
        }

        $total =0;
        $row2= $this->getDoctrine()->getRepository(Row::class)->findrow($order->getId());

        for ($y=0;$y<count($row2);$y++)
        {

            if($code != null) {
                $dp = $this->getDoctrine()->getRepository(DiscountProduct::class)->findOneBy(['product'=>$row2[$y]->getProduct(),'discount'=>$code]);
                if ($dp != null) {
                    $percentage = $dp->getDiscount()->getPercentage();
                    $oldPrice = $row2[$y]->getProduct()->getPrice() * (1 - $percentage / 100);
                    $price = $oldPrice * $row2[$y]->getAmount();
                    $total = $total + $price;
                } else {
                    $price = $row2[$y]->getProduct()->getPrice() * $row2[$y]->getAmount();
                    $total = $total + $price;
                }
            }else
                {
                    $price = $row2[$y]->getProduct()->getPrice() * $row2[$y]->getAmount();
                    $total = $total + $price;
                }

        }

        $order=$this->getDoctrine()->getRepository(Order::class)->find($order->getId());
        $em =$this->getDoctrine()->getManager();
        $order->setTotal($total);
        $em->persist($order);
        $em->flush();
        $this->addFlash('success', 'you have pay');
        $session->remove('cart');
        return $this->redirectToRoute("orderList");

    }

    /**
     * @Route("/customer/order/orderList", name="orderList")
     */
    public function orderList()
    {
        $order= $this->getDoctrine()->getRepository(Order::class)->findAll();
        return $this->render('customer/order.html.twig',['orders'=>$order]);
    }


}
