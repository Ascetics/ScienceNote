# 第07章 积分的技巧

分步积分法、变量替换、三角函数替换等。

---

**求不定积分$\int \frac{x-2}{x^2+2x+3} \mathrm{d}x$。**

$\begin{aligned}
\int \frac{x-2}{x^2+2x+3} \mathrm{d}x 
&=\int \frac{(x+1)-3}{(x+1)^2+2} \mathrm{d}x \\
&=\int \frac{(x+1)}{(x+1)^2+2} \mathrm{d}x - 3\int \frac{1}{(x+1)^2+2} \mathrm{d}x \\
&=\frac{1}{2}\ln(2+(x+1)^2)-\frac{3}{\sqrt{2}}\arctan \frac{x+1}{\sqrt{2}} +C \\
&=\frac{1}{2}\ln(x^2+2x+3)-\frac{3}{\sqrt{2}}\arctan \frac{x+1}{\sqrt{2}} +C
\end{aligned}$

---

**求不定积分$\int \frac{\cos x}{2\sin x + 3\cos x} \mathrm{d}x$。**

待定系数法。思路是将分子分为两部分，一部分可以与分母相除消除，另一部分是分母的导数。

设分子 $\cos x = A(2\sin x + 3\cos x) + B(2\sin x + 3\cos x)'$  
整理得到 $\cos x = (2A-3B)\sin x  + (3A+2B)\cos x$   
解方程组  

$\begin{cases}
2A-3B &= 0 \\
3A+2B &= 1
\end{cases}$

得到  

$\begin{cases}
A &= \frac{2}{13} \\
B &= \frac{3}{13}
\end{cases}$

那么

$\begin{aligned}
\int \frac{\cos x}{2\sin x + 3\cos x} \mathrm{d}x
&= \frac{3}{13}\int 1 \mathrm{d}x + \frac{2}{13}\int \frac{1}{2\sin x + 3\cos x} \mathrm{d}(2\sin x + 3\cos x) \\
&= \frac{3}{13}x+\frac{2}{13} \ln |2\sin x + 3\cos x| + C
\end{aligned}$

---


**求 $\int x^2(3-2\cos^2 x) \mathrm{d}x$。**

思路：多项式和正余弦乘积，求导后，多项式降幂，正余弦交替出现。

$\begin{aligned}
\int x^2(3-2\cos^2 x) \mathrm{d}x &\xlongequal{\quad 1+\cos 2x = 2\cos^2x \quad} \int x^2(2-\cos 2x) \mathrm{d}x \\
&= \int 2x^2 \mathrm{d}x - \int x^2\cos 2x \mathrm{d}x
\end{aligned}$

$\begin{cases}
(x^2\sin 2x)' &= 2x^2\cos 2x &+ &2x\sin 2x &  &         &\text{①} \\
(x\cos 2x)'   &=             &- &2x\sin 2x &+ &\cos 2x  &\text{②} \\
(\sin 2x)'    &=             &  &          &  &2\cos 2x &\text{③}
\end{cases}$

构造合适的系数，可以只保留我们需要的项 $\frac{1}{2}①+\frac{1}{2}②-\frac{1}{4}③=x^2\cos 2x$

原式=$\frac{2}{3}x^3 - \frac{1}{2}x^2\sin 2x - \frac{1}{2}x\cos 2x - \frac{1}{4}\sin 2x + C$

---

**求 $\int \frac{1}{x+x^3} \mathrm{d}x$、$\int \frac{4}{x+x^4} \mathrm{d}x$。**

思路：大方向是构造 $\ln{f(x)}$， $f(x)$ 是分母，其导数中的冗余项再想办法消除。

$\begin{aligned}
f(x)  &= \ln(x+x^B) \\
f'(x) &= \frac{1+Bx^{B-1}}{x+x^B}
\end{aligned}$

$\begin{aligned}
\int \frac{A}{x+x^B} \mathrm{d}x 
&= A(\int \frac{1+Bx^{B-1}}{x+x^B} \mathrm{d}x - \int \frac{Bx^{B-1}}{x+x^B} \mathrm{d}x) \\
&= A(\int \frac{1}{x+x^B} \mathrm{d}(x+x^B) - \frac{B}{B-1} \int \frac{1}{1+x^{B-1}} \mathrm{d}(1+x^{B-1})) \\ 
&= A(\ln|x+x^B| - \frac{B}{B-1}\ln|1+x^{B-1}|) \\
&= \frac{A}{B-1}\ln|\frac{x^B}{x+x^B}| + C
\end{aligned}$

所以

$\begin{aligned}
\int \frac{1}{x+x^3} \mathrm{d}x &= \frac{1}{2}\ln|\frac{x^3}{x+x^3}| + C \\
\int \frac{4}{x+x^4} \mathrm{d}x &= \frac{4}{3}\ln|\frac{x^4}{x+x^4}| + C
\end{aligned}$

---

**求 $\int \frac{x^2+1}{(x+2)(x+1)^2} \mathrm{d}x$。**

思路：待定系数法。设
$\frac{x^2+1}{(x+2)(x+1)^2}= \frac{A}{x+2} + \frac{B}{x+1} + \frac{C}{(x+1)^2}=\frac{(A+B)x^2+(2A+3B+C)x+(A+2B+2C)}{(x+2)(x+1)^2}$

解方程组
$\begin{cases}
&A  &+ &B  &  &   &= 1 \\
&2A &+ &3B &+ &C  &= 0 \\
&A  &+ &2B &+ &2C &= 1
\end{cases} 
\Rightarrow 
\left[
\begin{array}{ccc|c}
1 & 1 & 0 & 1\\
2 & 3 & 1 & 0\\
1 & 2 & 2 & 1
\end{array}
\right]
\Rightarrow 
\left[
\begin{array}{ccc|c}
1 & 0 & 0 & 5\\
0 & 1 & 0 & -4\\
0 & 0 & 1 & 2
\end{array}
\right]$

$\begin{aligned}
\int \frac{x^2+1}{(x+2)(x+1)^2} \mathrm{d}x
&= \int \frac{5}{x+2} - \frac{4}{x+1} + \frac{2}{(x+1)^2} \mathrm{d}x \\
&=5\ln|x+2|-4\ln|x+1|-\frac{2}{x+1}+C
\end{aligned}$

---

**求 $\int \frac{\mathrm{d}x}{\sqrt{x(4-x)}}$。**

$\begin{aligned}
\int \frac{\mathrm{d}x}{\sqrt{x(4-x)}}
&\xlongequal {\quad t=\frac{x-2}{2} \quad} \int \frac{1}{\sqrt{1-t^2}} \mathrm{d}t=\arcsin t + C= \arcsin(\frac{x-2}{2})+C
\end{aligned}$

---