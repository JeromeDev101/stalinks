<template>
  <div>
    <div class='row'>
      <div class='col-sm-12'>
        <div class='box'>
          <div class='box-header'>
            <div class='row'>
              <div class='col-md-12'>

                <h1 class='bg-primary text-white p-2 mt-3 with-arrow'>
                    <router-link :to="{ path: '/help' }">
                   <img
                      class=''
                      src='../../../../../images/writer-guide/back.png'
                    />
                    </router-link>
                    {{ $t('message.buyer_1.h1') }}
                   <router-link :to="{ path: '/help/buyer-guide-2' }">
                      <img
                      class=''
                      src='../../../../../images/writer-guide/next.png'
                    />
                  </router-link>
                </h1>
                <p>
                    {{ $t('message.buyer_1.p1') }}
                </p>
                <p>{{ $t('message.buyer_1.p2') }}</p>
                <ol class='content-styles' >
                  <li>{{ $t('message.buyer_1.ol1') }}</li>
                  <ul class="list-style-none">
                    <li>{{ $t('message.buyer_1.ul1') }}</li>

                    <li>{{ $t('message.buyer_1.ul2') }}</li>
                  </ul>
                  <ol type='a' >
                    <li>{{ $t('message.buyer_1.ul3') }}</li>
                    <li>{{ $t('message.buyer_1.ul4') }}</li>
                    <li>{{ $t('message.buyer_1.ul5') }}</li>
                    <li>{{ $t('message.buyer_1.ul6') }}</li>
                    <li>{{ $t('message.buyer_1.ul7') }}</li>
                    <li>
                        {{ $t('message.buyer_1.ul8') }}
                    </li>
                    <li>{{ $t('message.buyer_1.ul9') }}</li>
                    <li>
                        {{ $t('message.buyer_1.ul10') }}
                    </li>

                  </ol>
                   <img class="up" src='../../../../../images/buyer-guide/up.png' />
                  <li>{{ $t('message.buyer_1.ol2') }}</li>
                   <ul class="list-style-none">
                    <li>{{ $t('message.buyer_1.ul11') }}</li>

                    <li>{{ $t('message.buyer_1.ul12') }}</li>
                      </ul>
                    <ol type='a' >
                    <li>{{ $t('message.buyer_1.ul13') }}</li>
                    <li>{{ $t('message.buyer_1.ul14') }}</li>
                    <li>{{ $t('message.buyer_1.ul15') }}</li>
                    <li>
                        {{ $t('message.buyer_1.ul16') }}
                    </li>
                    <li>
                        {{ $t('message.buyer_1.ul17') }}
                    </li>


                  </ol>
                    <img class="up" src='../../../../../images/buyer-guide/buyer-guide-1.png' />
                    <li>{{ $t('message.buyer_1.ol3') }} </li>
                    <ul class="list-style-none">
                    <li>{{ $t('message.buyer_1.ul18') }}</li>

                    <li>{{ $t('message.buyer_1.ul19') }}</li>

                      </ul>
                      <ol type='a' >
                    <li>{{ $t('message.buyer_1.ul20') }}</li>
                    <li>
                        {{ $t('message.buyer_1.ul21') }}
                    </li>
                    <li>{{ $t('message.buyer_1.ul22') }}</li>
                    <li>
                        {{ $t('message.buyer_1.ul23') }}
                    </li>


                  </ol>
                   <img class="up" src='../../../../../images/buyer-guide/buyer-guide-2.png' />
                <p>{{ $t('message.buyer_1.p3') }}</p>

                 <p>{{ $t('message.buyer_1.p4') }}</p>
                </ol>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


</template>
  <style>
        .up{
            width: 100%;
            padding: 20px;
        }
        .content-styles li {
            font-size:17px;
            font-weight:600;
        }



        .with-arrow{
          display:flex;
          justify-content:space-between;
          align-items:center;
        }

    </style>
