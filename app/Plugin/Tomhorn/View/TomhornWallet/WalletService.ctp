<?xml version="1.0" encoding="utf-8" ?> 
<wsdl:definitions xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://GMS.Integration.Plugin/WalletService" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" targetNamespace="http://GMS.Integration.Plugin/WalletService">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://GMS.Integration.Plugin/WalletService">
      <s:element name="Deposit">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="partnerID" type="s:string"/>
            <s:element minOccurs="0" maxOccurs="1" name="sign" type="s:string"/>
            <s:element minOccurs="0" maxOccurs="1" name="name" type="s:string"/>
            <s:element minOccurs="1" maxOccurs="1" name="amount" type="s:decimal"/>
            <s:element minOccurs="0" maxOccurs="1" name="currency" type="s:string"/>
            <s:element minOccurs="1" maxOccurs="1" name="reference" type="s:long"/>
            <s:element minOccurs="1" maxOccurs="1" name="sessionID" type="s:long"/>
            <s:element minOccurs="1" maxOccurs="1" name="gameRoundID" type="s:long"/>
            <s:element minOccurs="0" maxOccurs="1" name="gameModule" type="s:string"/>
            <s:element minOccurs="1" maxOccurs="1" name="type" type="s:int"/>
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="DepositResponse">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="DepositResult" type="tns:DepositResult"/>
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:complexType name="DepositResult">
        <s:complexContent mixed="false">
          <s:extension base="tns:ServiceResult">
            <s:sequence>
              <s:element minOccurs="0" maxOccurs="1" name="Transaction" type="tns:Transaction"/>
            </s:sequence>
          </s:extension>
        </s:complexContent>
      </s:complexType>
      <s:complexType name="ServiceResult" abstract="true">
        <s:sequence>
          <s:element minOccurs="1" maxOccurs="1" name="Code" type="s:int"/>
          <s:element minOccurs="0" maxOccurs="1" name="Message" type="s:string"/>
        </s:sequence>
      </s:complexType>
      <s:complexType name="Transaction">
        <s:sequence>
          <s:element minOccurs="1" maxOccurs="1" name="ID" type="s:long"/>
          <s:element minOccurs="1" maxOccurs="1" name="Balance" type="s:decimal"/>
          <s:element minOccurs="0" maxOccurs="1" name="Currency" type="s:string"/>
        </s:sequence>
      </s:complexType>
      <s:element name="Withdraw">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="partnerID" type="s:string"/>
            <s:element minOccurs="0" maxOccurs="1" name="sign" type="s:string"/>
            <s:element minOccurs="0" maxOccurs="1" name="name" type="s:string"/>
            <s:element minOccurs="1" maxOccurs="1" name="amount" type="s:decimal"/>
            <s:element minOccurs="0" maxOccurs="1" name="currency" type="s:string"/>
            <s:element minOccurs="1" maxOccurs="1" name="reference" type="s:long"/>
            <s:element minOccurs="1" maxOccurs="1" name="sessionID" type="s:long"/>
            <s:element minOccurs="1" maxOccurs="1" name="gameRoundID" type="s:long"/>
            <s:element minOccurs="0" maxOccurs="1" name="gameModule" type="s:string"/>
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="WithdrawResponse">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="WithdrawResult" type="tns:WithdrawResult"/>
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:complexType name="WithdrawResult">
        <s:complexContent mixed="false">
          <s:extension base="tns:ServiceResult">
            <s:sequence>
              <s:element minOccurs="0" maxOccurs="1" name="Transaction" type="tns:Transaction"/>
            </s:sequence>
          </s:extension>
        </s:complexContent>
      </s:complexType>
      <s:element name="GetBalance">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="partnerID" type="s:string"/>
            <s:element minOccurs="0" maxOccurs="1" name="sign" type="s:string"/>
            <s:element minOccurs="0" maxOccurs="1" name="name" type="s:string"/>
            <s:element minOccurs="0" maxOccurs="1" name="currency" type="s:string"/>
            <s:element minOccurs="1" maxOccurs="1" name="sessionID" type="s:long"/>
            <s:element minOccurs="0" maxOccurs="1" name="gameModule" type="s:string"/>
            <s:element minOccurs="1" maxOccurs="1" name="type" type="s:int"/>
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="GetBalanceResponse">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="GetBalanceResult" type="tns:GetBalanceResult"/>
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:complexType name="GetBalanceResult">
        <s:complexContent mixed="false">
          <s:extension base="tns:ServiceResult">
            <s:sequence>
              <s:element minOccurs="0" maxOccurs="1" name="Balance" type="tns:Balance"/>
            </s:sequence>
          </s:extension>
        </s:complexContent>
      </s:complexType>
      <s:complexType name="Balance">
        <s:sequence>
          <s:element minOccurs="1" maxOccurs="1" name="Amount" type="s:decimal"/>
          <s:element minOccurs="0" maxOccurs="1" name="Currency" type="s:string"/>
        </s:sequence>
      </s:complexType>
      <s:element name="RollbackTransaction">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="partnerID" type="s:string"/>
            <s:element minOccurs="0" maxOccurs="1" name="sign" type="s:string"/>
            <s:element minOccurs="0" maxOccurs="1" name="name" type="s:string"/>
            <s:element minOccurs="1" maxOccurs="1" name="reference" type="s:long"/>
            <s:element minOccurs="1" maxOccurs="1" name="sessionID" type="s:long"/>
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="RollbackTransactionResponse">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="RollbackTransactionResult" type="tns:RollbackTransactionResult"/>
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:complexType name="RollbackTransactionResult">
        <s:complexContent mixed="false">
          <s:extension base="tns:ServiceResult"/>
        </s:complexContent>
      </s:complexType>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="DepositSoapIn">
    <wsdl:part name="parameters" element="tns:Deposit"/>
  </wsdl:message>
  <wsdl:message name="DepositSoapOut">
    <wsdl:part name="parameters" element="tns:DepositResponse"/>
  </wsdl:message>
  <wsdl:message name="WithdrawSoapIn">
    <wsdl:part name="parameters" element="tns:Withdraw"/>
  </wsdl:message>
  <wsdl:message name="WithdrawSoapOut">
    <wsdl:part name="parameters" element="tns:WithdrawResponse"/>
  </wsdl:message>
  <wsdl:message name="GetBalanceSoapIn">
    <wsdl:part name="parameters" element="tns:GetBalance"/>
  </wsdl:message>
  <wsdl:message name="GetBalanceSoapOut">
    <wsdl:part name="parameters" element="tns:GetBalanceResponse"/>
  </wsdl:message>
  <wsdl:message name="RollbackTransactionSoapIn">
    <wsdl:part name="parameters" element="tns:RollbackTransaction"/>
  </wsdl:message>
  <wsdl:message name="RollbackTransactionSoapOut">
    <wsdl:part name="parameters" element="tns:RollbackTransactionResponse"/>
  </wsdl:message>
  <wsdl:portType name="WalletServiceSoap">
    <wsdl:operation name="Deposit">
      <wsdl:input message="tns:DepositSoapIn"/>
      <wsdl:output message="tns:DepositSoapOut"/>
    </wsdl:operation>
    <wsdl:operation name="Withdraw">
      <wsdl:input message="tns:WithdrawSoapIn"/>
      <wsdl:output message="tns:WithdrawSoapOut"/>
    </wsdl:operation>
    <wsdl:operation name="GetBalance">
      <wsdl:input message="tns:GetBalanceSoapIn"/>
      <wsdl:output message="tns:GetBalanceSoapOut"/>
    </wsdl:operation>
    <wsdl:operation name="RollbackTransaction">
      <wsdl:input message="tns:RollbackTransactionSoapIn"/>
      <wsdl:output message="tns:RollbackTransactionSoapOut"/>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="WalletServiceSoap" type="tns:WalletServiceSoap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="Deposit">
      <soap:operation soapAction="http://GMS.Integration.Plugin/WalletService/Deposit" style="document"/>
      <wsdl:input>
        <soap:body use="literal"/>
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal"/>
      </wsdl:output>
    </wsdl:operation>
    <wsdl:operation name="Withdraw">
      <soap:operation soapAction="http://GMS.Integration.Plugin/WalletService/Withdraw" style="document"/>
      <wsdl:input>
        <soap:body use="literal"/>
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal"/>
      </wsdl:output>
    </wsdl:operation>
    <wsdl:operation name="GetBalance">
      <soap:operation soapAction="http://GMS.Integration.Plugin/WalletService/GetBalance" style="document"/>
      <wsdl:input>
        <soap:body use="literal"/>
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal"/>
      </wsdl:output>
    </wsdl:operation>
    <wsdl:operation name="RollbackTransaction">
      <soap:operation soapAction="http://GMS.Integration.Plugin/WalletService/RollbackTransaction" style="document"/>
      <wsdl:input>
        <soap:body use="literal"/>
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal"/>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="WalletServiceSoap12" type="tns:WalletServiceSoap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="Deposit">
      <soap12:operation soapAction="http://GMS.Integration.Plugin/WalletService/Deposit" style="document"/>
      <wsdl:input>
        <soap12:body use="literal"/>
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal"/>
      </wsdl:output>
    </wsdl:operation>
    <wsdl:operation name="Withdraw">
      <soap12:operation soapAction="http://GMS.Integration.Plugin/WalletService/Withdraw" style="document"/>
      <wsdl:input>
        <soap12:body use="literal"/>
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal"/>
      </wsdl:output>
    </wsdl:operation>
    <wsdl:operation name="GetBalance">
      <soap12:operation soapAction="http://GMS.Integration.Plugin/WalletService/GetBalance" style="document"/>
      <wsdl:input>
        <soap12:body use="literal"/>
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal"/>
      </wsdl:output>
    </wsdl:operation>
    <wsdl:operation name="RollbackTransaction">
      <soap12:operation soapAction="http://GMS.Integration.Plugin/WalletService/RollbackTransaction" style="document"/>
      <wsdl:input>
        <soap12:body use="literal"/>
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal"/>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
    <wsdl:service name="WalletService">
    <wsdl:port name="WalletServiceSoap" binding="tns:WalletServiceSoap">
      <soap:address location="http://82.214.112.210/tomhorn/callwallet/" />
    </wsdl:port>
    <wsdl:port name="WalletServiceSoap12" binding="tns:WalletServiceSoap12">
      <soap12:address location="http://82.214.112.210/tomhorn/callwallet/" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>