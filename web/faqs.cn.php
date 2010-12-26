<?php
	require_once('oj-header.php'); 
	require_once("./include/db_info.inc.php");
?>
<hr>
<center>
  <font size="+3"><?=$OJ_NAME?> Online Judge FAQ</font>
</center>
<hr>
<p><font color=green>Q</font>:������߲���ϵͳʹ��ʲô���ı������ͱ���ѡ��?<br>
  <font color=red>A</font>:ϵͳ������<a href="http://www.debian.org/">Debian</a>/<a href="http://www.ubuntu.com">Ubuntu</a> Linux. ʹ��<a href="http://gcc.gnu.org/">GNU GCC/G++</a> ��ΪC/C++������, <a href="http://www.freepascal.org">Free Pascal</a> ��Ϊpascal ������ ���� <a href="http://www.oracle.com/technetwork/java/index.html">sun-java-jdk1.6</a> ���� Java. ��Ӧ�ı���ѡ������:<br>
</p>
<table border="1">
  <tr>
    <td>C:</td>
    <td><font color=blue>gcc Main.c -o Main -Wall -lm --static -std=c99 -DONLINE_JUDGE</font></td>
  </tr>
  <tr>
    <td>C++:</td>
    <td><font color=blue>g++ Main.cc -o Main -O2 -Wall -lm --static -DONLINE_JUDGE</font></td>
  </tr>
  <tr>
    <td>Pascal:</td>
    <td><font color=blue>fpc -Co -Cr -Ct -Ci</font></td>
  </tr>
  <tr>
    <td>Java:</td>
    <td><font color="blue">javac -J-Xms32m -J-Xmx256m Main.java</font>
    <br>
    <font size="-1" color="red">*Java has 2 more seconds and 512M more memory when running and judging.</font>
    </td>
  </tr>
</table>
<p>  �������汾Ϊ��ϵͳ���������������汾������ֱ���ο���:<br>
  <font color=blue>gcc (Ubuntu/Linaro 4.4.4-14ubuntu5) 4.4.5</font><br>
  <font color=blue>glibc 2.3.6</font><br>
<font color=blue>Free Pascal Compiler version 2.4.0-2 [2010/03/06] for i386<br>
java version "1.6.0_22"<br>
</font></p>
<hr>
<p><font color=green>Q</font>:��������ȡ�����롢�������?<br>
  <font color=red>A</font>:��ĳ���Ӧ�ôӱ�׼���� stdin('Standard Input')��ȡ��� ��������������׼��� stdout('Standard Output').����,��C���Կ���ʹ�� 'scanf' ����C++����ʹ��'cin' �������룻��Cʹ�� 'printf' ����C++ʹ��'cout'�������.</p>
<p>�û���������ֱ�Ӷ�д�ļ�, ������������ܻ���Ϊ����ʱ���� "<font color=green>Runtime Error</font>"��<br>
  <br>
������ 1000��Ĳο���</p>
<p> C++:<br>
</p>
<pre><font color="blue">
#include &lt;iostream&gt;
using namespace std;
int main(){
    int a,b;
    while(cin >> a >> b)
        cout << a+b << endl;
	return 0;
}
</font></pre>
C:<br>
<pre><font color="blue">
#include &lt;stdio.h&gt;
int main(){
    int a,b;
    while(scanf("%d %d",&amp;a, &amp;b) != EOF)
        printf("%d\n",a+b);
	return 0;
}
</font></pre>
 PASCAL:<br>
<pre><font color="blue">
program p1001(Input,Output); 
var 
  a,b:Integer; 
begin 
   while not eof(Input) do 
     begin 
       Readln(a,b); 
       Writeln(a+b); 
     end; 
end.
</font></pre>
<br><br>

Java:<br>
<pre><font color="blue">
import java.util.*;
public class Main{
	public static void main(String args[]){
		Scanner cin = new Scanner(System.in);
		int a, b;
		while (cin.hasNext()){
			a = cin.nextInt(); b = cin.nextInt();
			System.out.println(a + b);
		}
	}
}</font></pre>

<hr>
<font color=green>Q</font>:Ϊʲô�ҵĳ������Լ��ĵ������������룬��ϵͳ�����ұ������!<br>
<font color=red>A</font>:GCC�ı����׼��VC6��Щ��ͬ�����ӷ���c/c++��׼:<br>
<ul>
  <li><font color=blue>main</font> �������뷵��<font color=blue>int</font>, <font color=blue>void main</font> �ĺ��������ᱨ�������<br> 
  <li><font color=green>i</font> ��ѭ����ʧȥ���� "<font color=blue>for</font>(<font color=blue>int</font> <font color=green>i</font>=0...){...}"<br>
  <li><font color=green>itoa</font> ����ansi��׼����.<br>
  <li><font color=green>__int64</font> ����ANSI��׼���壬ֻ����VCʹ��, ���ǿ���ʹ��<font color=blue>long long</font>����64λ������<br>
</ul>
<hr>
<font color=green>Q</font>:ϵͳ������Ϣ����ʲô��˼?<br>
<font color=red>A</font>:�������:<br>
<p><font color=blue>Pending</font> : ϵͳæ����Ĵ����Ŷӵȴ�. </p>
<p><font color=blue>Pending Rejudge</font>: ��Ϊ���ݸ��»�����ԭ��ϵͳ����������Ĵ�.</p>
<p><font color=blue>Compiling</font> : ���ڱ���.<br>
</p>
<p><font color="blue">Running &amp; Judging</font>: �������к��ж�.<br>
</p>
<p><font color=blue>Accepted</font> : ����ͨ��!<br>
  <br>
  <font color=blue>Presentation Error</font> : �𰸻�����ȷ�����Ǹ�ʽ���ԡ�<br>
  <br>
  <font color=blue>Wrong Answer</font> : �𰸲��ԣ�����ͨ���������ݵĲ��Բ���һ������ȷ�𰸣�һ��������û�뵽�ĵط�.<br>
  <br>
  <font color=blue>Time Limit Exceeded</font> : ���г���ʱ�����ƣ�������Ƿ�����ѭ��������Ӧ���и���ļ��㷽����<br>
  <br>
  <font color=blue>Memory Limit Exceeded</font> : �����ڴ����ƣ����ݿ�����Ҫѹ��������ڴ��Ƿ���й¶��<br>
  <br>
  <font color=blue>Output Limit Exceeded</font>: ����������ƣ�����������ȷ�𰸳�������.<br>
  <br>
  <font color=blue>Runtime Error</font> : ����ʱ���󣬷Ƿ����ڴ���ʣ�����Խ�磬ָ��Ư�ƣ����ý��õ�ϵͳ������<br>
</p>
<p>  <font color=blue>Compile Error</font> : ��������������ñ���������ϸ�����<br>
  <br>
</p>
<hr>
<font color=green>Q</font>:��βμ����߱���?<br>
<font color=red>A</font>:<a href=registerpage.php>ע��</a> һ���ʺţ�Ȼ��Ϳ�����ϰ����������б�Contests���Կ������ڽ��еı������μӡ�<br>
<br>
<hr>
<center>
  <font color=green size="+2">�������������<a href="bbs.php"><?=$OJ_NAME?>��̳ϵͳ</a></font>
</center>
<hr>
<center>
  <table width=100% border=0>
    <tr>
      <td align=right width=65%>
      <a href = "index.php"><font color=red><?=$OJ_NAME?></font></a> 
      <a href = "http://code.google.com/p/hustoj/source/detail?r=486"><font color=red>R650+</font></a></td>
    </tr>
  </table>
</center>
<?php require_once('oj-footer.php');?>
