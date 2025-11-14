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

思路：大方向是构造 $\ln{f(x)}$， $f(x)$ 是分母，其导数冗余性再消除。

$\begin{aligned}
f(x)  &= \ln(x+x^B) \\
f'(x) &= \frac{1+Bx^{B-1}}{x+x^B}
\end{aligned}$

$\begin{aligned}
\int \frac{A}{x+x^B} \mathrm{d}x 
&= A(\int \frac{1+Bx^{B-1}}{x+x^B} \mathrm{d}x - \int \frac{Bx^{B-1}}{x+x^B} \mathrm{d}x) \\
&= A(\int \frac{1}{x+x^B} \mathrm{d}(x+x^B) - \frac{B}{B-1} \int \frac{1}{1+x^{B-1}} \mathrm{d}(1+x^{B-1})) \\ 
&= A(\ln|x+x^B| + \frac{B}{B-1}\ln|1+x^{B-1}|) \\
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
A  &+  B &     &= 1 \\
2A &+ 3B &+ C  &= 0 \\
A  &+ 2B &+ 2C &= 1
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
\right]
\Rightarrow 
\begin{cases}
A &= 5 \\
B &= -4 \\
C &= 2
\end{cases}$

$\begin{aligned}
\int \frac{x^2+1}{(x+2)(x+1)^2} \mathrm{d}x
&= \int \frac{5}{x+2} - \frac{4}{x+1} + \frac{2}{(x+1)^2} \mathrm{d}x \\
&=5\ln|x+2|-4\ln|x+1|-\frac{2}{x+1}+C
\end{aligned}$

---

**求 $\lim_{n\to \infty} \int_0^1 \frac{x^n}{1+x} \mathrm{d}x$。**

$\begin{aligned}
\int_0^1 \frac{x^n}{1+x} \mathrm{d}x
&= \int_0^1 \frac{(1+x)((-1)^{0}x^{n-1} + (-1)^{1}x^{n-2} + \dots + (-1)^{n-2}x^{1} + (-1)^{n-1}x^{0}) + (-1)^n}{1+x} \mathrm{d}x \\
&= \int_0^1 ((-1)^0x^{n-1} + (-1)^1x^{n-2} + \dots + (-1)^{n-2}x^1 + (-1)^{n-1}x^0) + \frac{(-1)^n}{1+x} \mathrm{d}x \\
&= \left. \frac{(-1)^{0}x^{n}}{n} + \frac{(-1)^{1}x^{n-1}}{n-1} + \dots + \frac{(-1)^{n-2}x^{2}}{2} + \frac{(-1)^{n-1}x}{1} + (-1)^n\ln(1+x) \right|_0^1 \\
&= \left.\sum_{k=1}^{n}\frac{(-1)^{n-k}x^{k}}{k} + (-1)^n\ln(1+x) \right|_0^1 \\
&= (-1)^n(\sum_{k=1}^{n}\frac{(-1)^{k}}{k} + \ln 2) \\
\end{aligned}$

利用结论，调和级数奇数项和与偶数项和的差为
$1 - \frac{1}{2} + \frac{1}{3} - \frac{1}{4} + \frac{1}{5} - \frac{1}{6} + \dots  = \ln 2$

$\begin{aligned}
\lim_{n\to \infty} \int_0^1 \frac{x^n}{1+x} \mathrm{d}x
&= (-1)^{+\infty}(\sum_{k=1}^{{+\infty}}\frac{(-1)^{k}}{k} + \ln 2) = 0
\end{aligned}$

---

**求 $\int_0^\pi \frac{x\sin x}{1+\cos^2 x} \mathrm{d}x$。**

$\begin{aligned}
\int_0^\pi \frac{x\sin x}{1+\cos^2 x} \mathrm{d}x
&\xlongequal {\quad x=\frac{\pi}{2}-t \quad} \pi\int_{0}^{\frac{\pi}{2}} \frac{\cos t}{1+\sin^2 t} \mathrm{d}t \\
&\xlongequal {\quad u=\sin t \quad} \pi\int_{0}^{1} \frac{1}{1+u^2} \mathrm{d}u \\
&= \left. \pi\arctan u \right|_0^1 = \frac{\pi^2}{4}
\end{aligned}$